<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MerchantDashboardController extends Controller
{
    // 1. Halaman Overview Dashboard Merchant
    public function index(Request $request)
    {
        $merchantId = auth()->id();

        // =========================================================================
        // 1. DATA CARD STATUS UTAMA (MERCHANT KHUSUS)
        // =========================================================================
        $totalKategori = DB::table('items')
            ->where('user_id', $merchantId)
            ->distinct()
            ->count('category_id');

        $totalBarang = DB::table('items')->where('user_id', $merchantId)->count();
        
        // FIX SAKTI: Menghitung penyewaan aktif (approved) baik dari rentals.merchant_id ATAU items.user_id
        $penyewaanAktif = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->where('rentals.status', 'approved')
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->count();
        
        // FIX SAKTI: Total pendapatan dihitung dari transaksi sah (approved, returned, expired)
        $totalPendapatan = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->whereIn('rentals.status', ['approved', 'returned', 'expired'])
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->sum('rentals.total_price');

        // =========================================================================
        // 2. GRAFIK 1: ITEM PALING POPULER
        // =========================================================================
        $daysFilter = $request->get('days', 30);
        $popularItemsData = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('items.name', DB::raw('COUNT(rentals.id) as total_rented'))
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->whereIn('rentals.status', ['approved', 'returned', 'expired'])
            ->where('rentals.created_at', '>=', now()->subDays($daysFilter))
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_rented', 'desc')
            ->limit(5)
            ->get();

        $popularItemLabels = $popularItemsData->pluck('name')->toArray();
        $popularItemValues = $popularItemsData->pluck('total_rented')->toArray();

        // =========================================================================
        // 3. GRAFIK 2: PENDAPATAN 6 BULAN TERAKHIR
        // =========================================================================
        $monthlyEarnings = [];
        $monthlyLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthNum = $monthDate->month;
            $yearNum = $monthDate->year;
            $monthName = $monthDate->translatedFormat('M Y'); 
            
            $total = DB::table('rentals')
                ->join('items', 'rentals.item_id', '=', 'items.id')
                ->where(function($q) use ($merchantId) {
                    $q->where('rentals.merchant_id', $merchantId)
                      ->orWhere('items.user_id', $merchantId);
                })
                ->whereIn('rentals.status', ['approved', 'returned', 'expired'])
                ->whereYear('rentals.created_at', $yearNum)
                ->whereMonth('rentals.created_at', $monthNum)
                ->sum('rentals.total_price');
                
            $monthlyLabels[] = $monthName;
            $monthlyEarnings[] = (int) $total;
        }

        // Ambil riwayat penyewaan terbaru khusus produk merchant ini
        $recentRentals = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.image as item_image')
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->orderBy('rentals.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('merchant.dashboard', compact(
            'totalKategori', 'totalBarang', 'penyewaanAktif', 'totalPendapatan',
            'popularItemLabels', 'popularItemValues', 'daysFilter',
            'monthlyEarnings', 'monthlyLabels', 'recentRentals'
        ));
    }

    // 2. Data Penyewaan Khusus Merchant (FIX SAKTI: DUKUNG DUA PENGECEKAN KEPEMILIKAN)
    public function rentals()
    {
        $merchantId = auth()->id();

        $rentals = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.image as item_image')
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        return view('merchant.rentals_management', compact('rentals'));
    }

    // Aksi Approve untuk Merchant
    public function approve($id)
    {
        $merchantId = auth()->id();

        // Ambil data rental terkait dan pastikan milik merchant ini via rentals.merchant_id atau items.user_id
        $rental = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.stock', 'items.rent_mode', 'items.name as item_name')
            ->where('rentals.id', $id)
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan atau bukan milik unit Anda.');
        }

        if ($rental->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah pernah diproses sebelumnya!');
        }

        if ($rental->stock < 1) {
            return redirect()->back()->with('error', 'Gagal menyetujui, stok unit barang saat ini sudah habis!');
        }

        $now = Carbon::now();
        $duration = (int) $rental->duration;

        if (isset($rental->rent_mode) && $rental->rent_mode === 'bulan') {
            $expiredAt = $now->copy()->addMonths($duration);
        } else {
            $expiredAt = $now->copy()->addDays($duration);
        }

        DB::table('rentals')->where('id', $id)->update([
            'status' => 'approved',
            'expired_at' => $expiredAt,
            'updated_at' => $now
        ]);

        DB::table('items')->where('id', $rental->item_id)->decrement('stock', 1);
        DB::table('items')->where('id', $rental->item_id)->increment('total_rented', 1);

        DB::table('notifications')->insert([
            'user_id' => $rental->user_id,
            'item_id' => $rental->item_id,
            'title' => 'Pemesanan Disetujui! 🎉',
            'message' => "Hore! Pengajuan sewa unit \"{$rental->item_name}\" Anda telah disetujui oleh pemilik merchant. Silakan beri rating pengalaman Anda.",
            'is_read' => false,
            'is_rated' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        return redirect()->back()->with('success', 'Penyewaan unit sukses disetujui oleh Anda selaku pemilik barang, Bree!');
    }

    // Aksi Decline untuk Merchant
    public function decline($id)
    {
        $merchantId = auth()->id();

        $rental = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->where('rentals.id', $id)
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan atau bukan milik unit Anda.');
        }

        if ($rental->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah pernah diproses sebelumnya!');
        }

        DB::table('rentals')->where('id', $id)->update([
            'status' => 'declined',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Penyewaan unit barang berhasil Anda tolak.');
    }

    // 3. Konfirmasi Pengembalian Barang (Returned) oleh Merchant
    public function markAsReturned($id)
    {
        $merchantId = auth()->id();

        $rental = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->where('rentals.id', $id)
            ->where(function($q) use ($merchantId) {
                $q->where('rentals.merchant_id', $merchantId)
                  ->orWhere('items.user_id', $merchantId);
            })
            ->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Data penyewaan tidak ditemukan atau bukan milik Anda!');
        }

        if ($rental->status === 'returned') {
            return redirect()->back()->with('error', 'Unit sudah dikonfirmasi selesai sebelumnya!');
        }
        
        if ($rental->status !== 'approved' && $rental->status !== 'expired') {
            return redirect()->back()->with('error', 'Transaksi belum disetujui atau tidak valid untuk dikembalikan!');
        }

        DB::table('items')->where('id', $rental->item_id)->increment('stock', 1);

        DB::table('rentals')->where('id', $id)->update([
            'status' => 'returned',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Status unit berhasil dikonfirmasi selesai dikembalikan, stok otomatis bertambah kembali (+1)!');
    }

    // 4. Halaman List Stok Barang Merchant
    public function stock()
    {
        $merchantId = auth()->id();
        
        $items = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->where('items.user_id', $merchantId)
            ->select('items.*', 'categories.name as category_name')
            ->get();

        return view('merchant.stock_management', compact('items'));
    }

    // 5. Update Stok Barang
    public function updateStock(Request $request, $id)
    {
        $request->validate(['stock' => 'required|integer|min:0']);

        $item = DB::table('items')->where('id', $id)->where('user_id', auth()->id())->first();
        if (!$item) return redirect()->back()->with('error', 'Barang tidak ditemukan!');

        DB::table('items')->where('id', $id)->update([
            'stock' => $request->stock,
            'status' => $request->stock == 0 ? 'empty' : 'ready',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Stok barang berhasil diperbarui!');
    }
}