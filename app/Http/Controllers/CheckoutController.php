<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str; 

class CheckoutController extends Controller
{
    public function storeCheckout(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'duration' => 'required|integer|min:1',
            'applied_promo_code' => 'nullable|string', // Validasi inputan hidden promo dari view
        ]);

        $item = DB::table('items')->where('id', $id)->first();
        if (!$item || $item->stock < 1) {
            return redirect('/')->with('error', 'Unit tidak tersedia untuk disewa.');
        }

        $duration = (int) $request->duration;
        $now = Carbon::now();

        // 1. Hitung Subtotal Harga Sewa Awal
        $priceClean = (float) str_replace('.', '', $item->price);
        $subtotal = $priceClean * $duration;
        $discount = 0;

        // 2. Jika user menerapkan kode promo, hitung ulang potongannya untuk disimpan di database
        if ($request->filled('applied_promo_code')) {
            $promo = DB::table('promo_codes')
                ->where('code', strtoupper($request->applied_promo_code))
                ->first();

            if ($promo && (!$promo->expired_at || !Carbon::parse($promo->expired_at)->isPast()) && ($promo->total_used < $promo->max_uses)) {
                if ($promo->type === 'percentage') {
                    $discount = $subtotal * ($promo->reward_value / 100);
                } else {
                    $discount = (float) $promo->reward_value;
                }
                
                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }

                // Increment kuota terpakai pada kode promo tersebut
                DB::table('promo_codes')->where('id', $promo->id)->increment('total_used');
            }
        }

        // Hitung Nilai Akhir Setelah Potongan Diskon Kupon
        $grandTotal = $subtotal - $discount;

        // GENERATE KODE ORDER UNIK 
        $orderCode = 'DR-' . $now->format('Ymd') . '-' . strtoupper(Str::random(4));

        // =========================================================================
        // FIX TOTAL SESUAI ERD: Memasukkan merchant_id dari kolom user_id milik tabel items
        // =========================================================================
        DB::table('rentals')->insert([
            'order_code' => $orderCode, 
            'user_id' => auth()->id(),              // ID Customer yang sedang login menyewa
            'merchant_id' => $item->user_id,        // ID Merchant (diambil dari user_id tabel items)
            'item_id' => $id,
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'duration' => $duration,
            'status' => 'pending', 
            'expired_at' => null,  
            'total_price' => $grandTotal, 
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect('/history-order')->with('success', 'Pemesanan berhasil diajukan! Menunggu persetujuan pemilik unit.');
    }

    // =========================================================================
    // ENGINE FILTER VALIDASI KODE VOUCHER PROMO
    // =========================================================================
    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
            'subtotal' => 'required|numeric'
        ]);

        // 1. Cari kode promo aktif di DB
        $promo = DB::table('promo_codes')
            ->where('code', strtoupper($request->promo_code))
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Waduh! Kode promo tidak valid atau tidak ditemukan.'
            ], 404);
        }

        // 2. Cek waktu expired kupon
        if ($promo->expired_at && Carbon::parse($promo->expired_at)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Yaaah, kode promo ini sudah kedaluwarsa.'
            ], 400);
        }

        // 3. Cek sisa kuota limit pemakaian kupon
        if ($promo->total_used >= $promo->max_uses) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, kuota penggunaan kode promo ini sudah habis!'
            ], 400);
        }

        // 4. Hitung nominal potongan
        $subtotal = (float) $request->subtotal;
        $discount = 0;

        if ($promo->type === 'percentage') {
            $discount = $subtotal * ($promo->reward_value / 100);
        } else {
            $discount = (float) $promo->reward_value;
        }

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $grandTotal = $subtotal - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Mantap! Kode promo berhasil diterapkan.',
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'promo_code_string' => $promo->code
        ]);
    }
}