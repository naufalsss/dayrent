<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function storeCheckout(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'duration' => 'required|integer|min:1',
        ]);

        $item = DB::table('items')->where('id', $id)->first();
        if (!$item || $item->stock < 1) {
            return redirect('/')->with('error', 'Unit tidak tersedia untuk disewa.');
        }

        // Paksa duration menjadi murni Integer (int)
        $duration = (int) $request->duration;
        $now = Carbon::now();

        // =========================================================================
        // REKAYASA LANGKAH 1: KEMBALIKAN KE STATUS PENDING & TUNDA HITUNG MUNDUR
        // =========================================================================
        // 1. Masukkan data transaksi rentals dengan status antrean pending
        DB::table('rentals')->insert([
            'user_id' => auth()->id(),
            'item_id' => $id,
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'duration' => $duration,
            'status' => 'pending', // KUNCI: Status awal wajib pending agar divalidasi admin
            'expired_at' => null,  // Set null dulu, waktu dihitung sejak admin klik Approve
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Kodingan logika decrement (potong stok) lama di sini sudah gw hapus bersih, 
        // karena stok baru berkurang ketika admin sudah resmi melakukan approval.

        // Lempar balik ke rute riwayat order user dengan aman
        return redirect()->route('history.order')->with('success', 'Pemesanan berhasil diajukan! Menunggu persetujuan admin.');
    }
}