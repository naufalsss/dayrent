<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    // Aksi submit rating dari modal pop-up user
    public function submitRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // 1. Ambil data notifikasinya
        $notification = DB::table('notifications')->where('id', $id)->first();
        if (!$notification || $notification->is_rated) {
            return redirect()->back()->with('error', 'Anda sudah memberikan rating untuk transaksi ini.');
        }

        // 2. Tandai notifikasi tersebut bahwa rating sudah diisi agar tombol tidak diklik lagi
        DB::table('notifications')->where('id', $id)->update([
            'is_rated' => true,
            'is_read' => true,
            'updated_at' => now()
        ]);

        // 3. Simpan review bintang mentah ke tabel pembantu (kita manfaatkan tabel rentals kolom rating jika ada, atau buat simulasi rata-rata langsung)
        // Agar ringkas tanpa buat tabel review baru, kita langsung lakukan rekayasa matematika rata-rata di kolom items:
        $item = DB::table('items')->where('id', $notification->item_id)->first();
        
        // Rumus Rata-Rata Baru: ((Rating Saat Ini * (Tersewa - 1)) + Input Bintang Baru) / Tersewa
        $currentRating = (float) $item->rating;
        $totalRented = (int) $item->total_rented;
        
        if ($currentRating == 0) {
            $newAverage = $request->rating;
        } else {
            $newAverage = (($currentRating * ($totalRented - 1)) + $request->rating) / $totalRented;
        }

        // 4. Update rating dinamis baru ke tabel items (dibulatkan 1 angka di belakang koma)
        DB::table('items')->where('id', $notification->item_id)->update([
            'rating' => round($newAverage, 1)
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas penilaian rating Anda, Bree!');
    }
}