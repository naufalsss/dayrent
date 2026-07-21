<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    // Aksi submit rating dari modal pop-up user
    public function submitRating(Request $request, $id)
    {
        // 1. Validasi input bintang (rating) dan komentar (review_comment) dari form
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_comment' => 'required|string|max:500',
        ]);

        // 2. Ambil data notifikasinya
        $notification = DB::table('notifications')->where('id', $id)->first();
        if (!$notification || $notification->is_rated) {
            return redirect()->back()->with('error', 'Anda sudah memberikan rating untuk transaksi ini.');
        }

        // 3. PENCATATAN DINAMIS: Update status rated, read, dan isi kolom 'message' dengan rating + komentar dari user
        // Kita simpan rating angka di depan komentar dengan pemisah khusus agar mempermudah pemanggilan dinamis di marquee
        $encryptedReviewMessage = $request->rating . '||' . $request->review_comment;

        DB::table('notifications')->where('id', $id)->update([
            'message' => $encryptedReviewMessage, // Menyimpan ulasan asli buatan user
            'is_rated' => true,
            'is_read' => true,
            'updated_at' => now()
        ]);

        // 4. Hitung rekayasa matematika rata-rata rating baru untuk unit barang terkait
        $item = DB::table('items')->where('id', $notification->item_id)->first();
        
        $currentRating = (float) $item->rating;
        $totalRented = (int) $item->total_rented;
        
        if ($currentRating == 0) {
            $newAverage = $request->rating;
        } else {
            $newAverage = (($currentRating * ($totalRented - 1)) + $request->rating) / $totalRented;
        }

        // 5. Update rating dinamis baru ke tabel items (dibulatkan 1 angka di belakang koma)
        DB::table('items')->where('id', $notification->item_id)->update([
            'rating' => round($newAverage, 1)
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas penilaian rating dan ulasan Anda, Bree!');
    }

    // Aksi untuk menutup (dismiss/abaikan) notifikasi rating
    public function dismissRating($id)
    {
        $notification = DB::table('notifications')->where('id', $id)->first();
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan']);
        }

        DB::table('notifications')->where('id', $id)->update([
            'is_rated' => true,
            'is_read' => true,
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil ditutup']);
    }
}