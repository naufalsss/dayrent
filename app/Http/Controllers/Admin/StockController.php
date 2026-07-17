<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    // 1. Menampilkan Halaman List Stok Barang
    public function index()
    {
        // FIX AMAN: Mengambil data stok milik admin sendiri ATAU data bawaan awal yang user_id-nya kosong (NULL)
        $items = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->where(function($query) {
                $query->where('items.user_id', auth()->id())
                      ->orWhereNull('items.user_id');
            })
            ->select('items.*', 'categories.name as category_name')
            ->get();

        return view('admin.stock_management', compact('items'));
    }

    // 2. Fitur Mengubah / Update Jumlah Stok Barang
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        // PROTEKSI: Pastikan barang yang mau di-update stoknya memang milik admin yang login
        $item = DB::table('items')->where('id', $id)->where('user_id', auth()->id())->first();
        if (!$item) {
            return redirect()->back()->with('error', 'Data barang tidak ditemukan atau bukan milik Anda!');
        }

        DB::table('items')
            ->where('id', $id)
            ->update([
                'stock' => $request->stock,
                'status' => $request->stock == 0 ? 'empty' : 'ready',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Stok barang berhasil diperbarui! 📦');
    }

    // 3. Fitur Menghapus Angka Stok (Reset Jadi 0)
    public function deleteStock($id)
    {
        // PROTEKSI: Pastikan barang yang mau di-reset stoknya memang milik admin yang login
        $item = DB::table('items')->where('id', $id)->where('user_id', auth()->id())->first();
        if (!$item) {
            return redirect()->back()->with('error', 'Data barang tidak ditemukan atau bukan milik Anda!');
        }

        DB::table('items')
            ->where('id', $id)
            ->update([
                'stock' => 0,
                'status' => 'empty',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Stok berhasil dikosongkan! 🗑️');
    }
}