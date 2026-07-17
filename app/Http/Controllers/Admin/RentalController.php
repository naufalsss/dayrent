<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // FIX TAMBAHAN: Wajib dipanggil untuk memproses hitung tanggal expired_at

class RentalController extends Controller
{
    // Menampilkan data pesanan masuk (Fleksibel untuk Admin Global & Merchant Spesifik)
    // Menampilkan data pesanan masuk (Default: Hanya Admin, Fleksibel Search Merchant)
    public function index(Request $request)
    {
        $now = Carbon::now();
        $myId = auth()->id();
        $isAdmin = auth()->user()->role === 'admin';

        // Tangkap parameter filter waktu dan pencarian merchant dari form view
        $timeStatus = $request->get('time_status', 'all');
        $searchMerchant = $request->get('search_merchant'); // Input nama toko merchant

        // =========================================================================
        // ENGINE DETEKSI OTOMATIS EXPIRED RENTAL
        // =========================================================================
        $expireQuery = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->where('rentals.status', 'approved')
            ->whereNotNull('rentals.expired_at')
            ->where('rentals.expired_at', '<=', $now);

        if (!$isAdmin) {
            $expireQuery->where('rentals.merchant_id', $myId);
        }

        $toExpire = $expireQuery->select('rentals.*', 'items.name as item_name')->get();

        foreach ($toExpire as $rental) {
            DB::table('rentals')->where('id', $rental->id)->update([
                'status' => 'expired',
                'updated_at' => $now
            ]);

            DB::table('notifications')->insert([
                'user_id' => $rental->user_id,
                'item_id' => $rental->item_id,
                'title' => '🚨 Masa Sewa Habis!',
                'message' => "Masa penyewaan unit \"{$rental->item_name}\" Anda telah habis. Harap segera mengembalikan unit dan jangan lupa beri rating ulasan.",
                'is_read' => false,
                'is_rated' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        // =========================================================================

        // Mulai Query Utama Penarikan Data (Gunakan Left Join ke users/merchant jika dibutuhkan info toko)
        $query = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.image as item_image');

        if ($isAdmin) {
            if (!empty($searchMerchant)) {
                // SKENARIO A: Admin sedang mencari spesifik nama toko merchant
                // Menghubungkan rentals ke tabel users (karena merchant terdaftar di users dengan role merchant)
                $query->join('users', 'rentals.merchant_id', '=', 'users.id')
                      ->where('users.role', 'merchant')
                      ->where('users.name', 'like', '%' . $searchMerchant . '%');
            } else {
                // SKENARIO B: Default Admin (Awal Buka), Hanya tampilkan barang milik Admin
                // Barang admin ditandai dengan merchant_id null ATAU merchant_id = ID Admin itu sendiri
                $query->where(function($q) use ($myId) {
                    $q->whereNull('rentals.merchant_id')
                      ->orWhere('rentals.merchant_id', $myId);
                });
            }
        } else {
            // SKENARIO C: Akun yang login adalah Merchant biasa, kunci total hanya barang miliknya
            $query->where('rentals.merchant_id', $myId);
        }

        // =========================================================================
        // LOGIKA FILTER MASA SEWA 
        // =========================================================================
        if ($timeStatus !== 'all') {
            if ($timeStatus === 'active') {
                $query->where('rentals.status', 'approved')
                      ->whereNotNull('rentals.expired_at')
                      ->where('rentals.expired_at', '>', $now);
            } 
            elseif ($timeStatus === 'critical') {
                $oneHourFromNow = Carbon::now()->addHour();
                $query->where('rentals.status', 'approved')
                      ->whereNotNull('rentals.expired_at')
                      ->where('rentals.expired_at', '>', $now)
                      ->where('rentals.expired_at', '<=', $oneHourFromNow);
            } 
            elseif ($timeStatus === 'expired') {
                $query->where(function($q) use ($now) {
                    $q->whereIn('rentals.status', ['expired', 'returned'])
                      ->orWhere(function($subQ) use ($now) {
                          $subQ->where('rentals.status', 'approved')
                               ->whereNotNull('rentals.expired_at')
                               ->where('rentals.expired_at', '<=', $now);
                      });
                });
            }
        }

        $rentals = $query->orderBy('rentals.created_at', 'desc')->get();

        // Lempar data searchMerchant ke view agar form pencarian tidak ter-reset otomatis saat submit
        return view('admin.rentals_management', compact('rentals', 'timeStatus', 'searchMerchant'));
    }

    // Proses hitung mundur & potong stok dijalankan SAAT APPROVAL
    public function approve($id)
    {
        // 1. Ambil data rental terkait
        $rental = DB::table('rentals')->where('id', $id)->first();
        
        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        // FIX HAK AKSES UTAMA: Mengizinkan jika dia admin Super atau dia adalah Merchant pemilik barang tersebut
        if (auth()->user()->role !== 'admin' && auth()->id() !== $rental->merchant_id) {
            return redirect()->back()->with('error', 'Gagal! Anda tidak memiliki hak akses mengelola pesanan unit barang ini.');
        }

        // 2. Ambil data item barang untuk cek ketersediaan stok dan rent_mode-nya
        $item = DB::table('items')->where('id', $rental->item_id)->first();
        if (!$item || $item->stock < 1) {
            return redirect()->back()->with('error', 'Gagal menyetujui, stok unit barang saat ini sudah habis!');
        }

        // 3. Mulai kalkulasi waktu expired_at sejak tombol disetujui detik ini
        $now = Carbon::now();
        $duration = (int) $rental->duration;

        if (isset($item->rent_mode) && $item->rent_mode === 'bulan') {
            $expiredAt = $now->copy()->addMonths($duration);
        } else {
            // MODIFIKASI SIMULASI SIMPANAN: otomatis expired dalam 2 menit
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

        // 6. Tambah hitungan total dinwis tersewa pada barang tersebut (+1)
        DB::table('items')->where('id', $rental->item_id)->increment('total_rented', 1);

        // 7. Kirim data notifikasi ke database agar muncul di lonceng navbar milik user
        DB::table('notifications')->insert([
            'user_id' => $rental->user_id,
            'item_id' => $rental->item_id,
            'title' => 'Pemesanan Disetujui! 🎉',
            'message' => "Hore! Pengajuan sewa unit \"{$item->name}\" Anda telah disetujui. Silakan beri rating pengalaman Anda.",
            'is_read' => false,
            'is_rated' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        return redirect()->back()->with('success', 'Penyewaan berhasil disetujui! Stok otomatis dipotong dan notifikasi telah dikirim.');
    }

    // Konfirmasi "Dikembalikan" untuk restock item (FIXED LOGIC)
    public function confirmReturn($id)
    {
        $rental = DB::table('rentals')->where('id', $id)->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        // FIX HAK AKSES UTAMA
        if (auth()->user()->role !== 'admin' && auth()->id() !== $rental->merchant_id) {
            return redirect()->back()->with('error', 'Gagal! Anda tidak memiliki hak akses mengelola pesanan unit barang ini.');
        }

        // FIX VALIDASI PERLONGGAR: Izinkan return jika status DB adalah 'expired' ATAU 'approved' yang waktunya sudah habis lewat simulasi
        $isExpiredApproved = ($rental->status === 'approved' && isset($rental->expired_at) && strtotime($rental->expired_at) <= time());
        
        if ($rental->status !== 'expired' && !$isExpiredApproved) {
            return redirect()->back()->with('error', 'Transaksi ini belum memasuki masa expired / sudah diproses.');
        }

        // Kembalikan jumlah stok barang (+1)
        DB::table('items')->where('id', $rental->item_id)->increment('stock', 1);

        // Ubah status ke 'returned'
        DB::table('rentals')->where('id', $id)->update([
            'status' => 'returned', 
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Sukses! Unit telah resmi dikembalikan dan stok item berhasil bertambah kembali (+1).');
    }

    // Aksi Decline (Murni untuk menolak pesanan baru masuk)
    public function decline($id)
    {
        $rental = DB::table('rentals')->where('id', $id)->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        // FIX HAK AKSES UTAMA
        if (auth()->user()->role !== 'admin' && auth()->id() !== $rental->merchant_id) {
            return redirect()->back()->with('error', 'Gagal! Anda tidak memiliki hak akses mengelola pesanan unit barang ini.');
        }

        DB::table('rentals')->where('id', $id)->update([
            'status' => 'declined',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Penyewaan berhasil ditolak!');
    }

    // Fungsi untuk menghapus riwayat transaksi penyewaan oleh Admin / Pemilik Barang
    public function destroy($id)
    {
        $rental = DB::table('rentals')->where('id', $id)->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        // FIX HAK AKSES UTAMA
        if (auth()->user()->role !== 'admin' && auth()->id() !== $rental->merchant_id) {
            return redirect()->back()->with('error', 'Gagal! Anda tidak memiliki hak akses mengelola pesanan unit barang ini.');
        }

        if ($rental->status === 'approved') {
            DB::table('items')->where('id', $rental->item_id)->increment('stock', 1);
        }

        DB::table('rentals')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus dan stok unit telah dibatalkan/dikembalikan!');
    }
}