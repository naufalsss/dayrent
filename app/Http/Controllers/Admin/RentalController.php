<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // FIX TAMBAHAN: Wajib dipanggil untuk memproses hitung tanggal expired_at

class RentalController extends Controller
{
    // Menampilkan semua data pesanan masuk ke Admin
    public function index()
    {
        $now = Carbon::now();

        // =========================================================================
        // ENGINE DETEKSI OTOMATIS: Paksa sewa yang habis menjadi status 'expired'
        // =========================================================================
        DB::table('rentals')
            ->where('status', 'approved')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', $now)
            ->update([
                'status' => 'expired',
                'updated_at' => $now
            ]);
        // =========================================================================

        $rentals = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.image as item_image')
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        return view('admin.rentals_management', compact('rentals'));
    }

    // FIX UTAMA (LANGKAH 2): Proses hitung mundur & potong stok dijalankan SAAT APPROVAL ADMIN
    public function approve($id)
    {
        // 1. Ambil data rental terkait
        $rental = DB::table('rentals')->where('id', $id)->first();
        
        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
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
            $expiredAt = $now->copy()->addDays($duration);
        }

        // 4. Update status transaksi rentals dan suntikkan tanggal kedaluwarsanya
        DB::table('rentals')->where('id', $id)->update([
            'status' => 'approved',
            'expired_at' => $expiredAt,
            'updated_at' => $now
        ]);

        // 5. Potong stok unit barang di database sebanyak 1
        DB::table('items')->where('id', $rental->item_id)->decrement('stock', 1);

        // =========================================================================
        // SUNTIKAN BARU (LANGKAH 2): AUTO-INCREMENT TERSEWA & GENERATE NOTIFIKASI
        // =========================================================================
        
        // 6. Tambah hitungan total dinamis tersewa pada barang tersebut (+1)
        DB::table('items')->where('id', $rental->item_id)->increment('total_rented', 1);

        // 7. Kirim data notifikasi ke database agar muncul di lonceng navbar milik user
        DB::table('notifications')->insert([
            'user_id' => $rental->user_id,
            'item_id' => $rental->item_id,
            'title' => 'Pemesanan Disetujui! 🎉',
            'message' => "Hore! Pengajuan sewa unit \"{$item->name}\" Anda telah disetujui admin. Silakan beri rating pengalaman Anda.",
            'is_read' => false,
            'is_rated' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        // =========================================================================

        return redirect()->back()->with('success', 'Penyewaan berhasil disetujui! Stok otomatis dipotong, angka tersewa bertambah, dan notifikasi rating dikirim.');
    }

    // Aksi Decline
    public function decline($id)
    {
        DB::table('rentals')->where('id', $id)->update([
            'status' => 'declined',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Penyewaan berhasil ditolak!');
    }

    // Fungsi untuk menghapus riwayat transaksi penyewaan oleh Admin
    public function destroy($id)
    {
        // 1. Cari data transaksi rentals yang mau dihapus terlebih dahulu
        $rental = DB::table('rentals')->where('id', $id)->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        // 2. FIX SAKTI: Jika transaksi yang dihapus statusnya 'approved' (sedang berjalan/timer aktif),
        // maka sebelum dihapus, kita kembalikan dulu stok barangnya (tambah 1) ke katalog depan!
        if ($rental->status === 'approved') {
            DB::table('items')->where('id', $rental->item_id)->increment('stock', 1);
        }

        // 3. Setelah stok aman diselamatkan, baru hapus total transaksinya dari database
        DB::table('rentals')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus dan stok unit telah dibatalkan/dikembalikan!');
    }
}