<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MerchantDashboardController extends Controller
{
    // 1. Halaman Overview Dashboard Merchant
    public function index()
    {
        $merchantId = auth()->id();

        // Hitung total unit barang milik merchant ini saja
        $totalItems = DB::table('items')->where('user_id', $merchantId)->count();

        // Hitung total transaksi penyewaan milik merchant ini saja
        $totalRentals = DB::table('rentals')->where('merchant_id', $merchantId)->count();

        // Ambil riwayat penyewaan terbaru khusus produk merchant ini
        // FIX GAMBAR: Menambahkan items.image agar gambar produk muncul di halaman overview dashboard
        $recentRentals = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.image as item_image')
            ->where('rentals.merchant_id', $merchantId)
            ->orderBy('rentals.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('merchant.dashboard', compact('totalItems', 'totalRentals', 'recentRentals'));
    }

    // 2. Data Penyewaan Khusus Merchant
    public function rentals()
    {
        $merchantId = auth()->id();

        $rentals = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.image as item_image')
            ->where('rentals.merchant_id', $merchantId)
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        return view('merchant.rentals_management', compact('rentals'));
    }

    // ADDONS SAKTI: Fungsi Aksi Approve untuk Merchant
    public function approve($id)
    {
        $merchantId = auth()->id();

        // 1. Ambil data rental terkait dan pastikan milik merchant ini
        $rental = DB::table('rentals')->where('id', $id)->where('merchant_id', $merchantId)->first();
        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan atau bukan milik unit Anda.');
        }

        // 2. Ambil data item barang untuk cek ketersediaan stok
        $item = DB::table('items')->where('id', $rental->item_id)->first();
        if (!$item || $item->stock < 1) {
            return redirect()->back()->with('error', 'Gagal menyetujui, stok unit barang saat ini sudah habis!');
        }

        // 3. Mulai kalkulasi waktu expired_at dinamis
        $now = Carbon::now();
        $duration = (int) $rental->duration;

        if (isset($item->rent_mode) && $item->rent_mode === 'bulan') {
            $expiredAt = $now->copy()->addMonths($duration);
        } else {
            // otomatis expired dalam 2 menit mengikuti simulasi awal kelompok lu
            $expiredAt = $now->copy()->addMinutes(2);
        }

        // 4. Update status transaksi rentals dan suntikkan tanggal kedaluwarsanya
        DB::table('rentals')->where('id', $id)->update([
            'status' => 'approved',
            'expired_at' => $expiredAt,
            'updated_at' => $now
        ]);

        // 5. Potong stok unit barang di database sebanyak 1
        DB::table('items')->where('id', $rental->item_id)->decrement('stock', 1);

        // 6. Tambah hitungan total tersewa pada barang tersebut (+1)
        DB::table('items')->where('id', $rental->item_id)->increment('total_rented', 1);

        // 7. Kirim data notifikasi ke database agar muncul di lonceng navbar milik customer
        DB::table('notifications')->insert([
            'user_id' => $rental->user_id,
            'item_id' => $rental->item_id,
            'title' => 'Pemesanan Disetujui! 🎉',
            'message' => "Hore! Pengajuan sewa unit \"{$item->name}\" Anda telah disetujui oleh pemilik merchant. Silakan beri rating pengalaman Anda.",
            'is_read' => false,
            'is_rated' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        return redirect()->back()->with('success', 'Penyewaan unit sukses disetujui oleh Anda selaku pemilik barang, Bree!');
    }

    // Fungsi Aksi Decline untuk Merchant
    public function decline($id)
    {
        $merchantId = auth()->id();

        $rental = DB::table('rentals')->where('id', $id)->where('merchant_id', $merchantId)->first();
        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan atau bukan milik unit Anda.');
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

        // Pastikan rental tersebut memang milik unit barang milik merchant ini
        $rental = DB::table('rentals')->where('id', $id)->where('merchant_id', $merchantId)->first();
        if (!$rental) {
            return redirect()->back()->with('error', 'Data penyewaan tidak ditemukan atau bukan milik Anda!');
        }

        // Kembalikan jumlah stok barang (+1) ke merchant
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
            ->where('items.user_id', $merchantId) // Kunci: Hanya barang milik merchant ini
            ->select('items.*', 'categories.name as category_name')
            ->get();

        return view('merchant.stock_management', compact('items'));
    }

    // 5. Update Stok Barang
    public function updateStock(Request $request, $id)
    {
        $request->validate(['stock' => 'required|integer|min:0']);

        // Pastikan barang tersebut milik merchant yang sedang login
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