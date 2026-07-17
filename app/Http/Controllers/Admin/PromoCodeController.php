<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromoCodeController extends Controller
{
    // 1. Menampilkan Daftar Semua Kode Promo di Dashboard Admin
    public function index()
    {
        $promos = DB::table('promo_codes')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.promo_codes_management', compact('promos'));
    }

    // 2. Menyimpan Kode Promo Baru dari Form Admin
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code|max:50',
            'type' => 'required|in:percentage,nominal',
            'reward_value' => 'required|numeric|min:1',
            'max_uses' => 'required|integer|min:1',
            'expired_at' => 'required|date|after:today',
        ]);

        DB::table('promo_codes')->insert([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'reward_value' => $request->reward_value,
            'max_uses' => $request->max_uses,
            'total_used' => 0,
            'expired_at' => Carbon::parse($request->expired_at)->endOfDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Hore! Kode promo baru berhasil ditambahkan ke sistem.');
    }

    // 3. Menghapus Kode Promo
    public function destroy($id)
    {
        DB::table('promo_codes')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Kode promo berhasil dihapus dari sistem.');
    }
}