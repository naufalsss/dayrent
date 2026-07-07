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
        // Mengambil semua data barang join dengan kategorinya agar informatif
        $items = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
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

        DB::table('items')
            ->where('id', $id)
            ->update([
                'stock' => $request->stock,
                // Otomatis ubah status ke 'empty' jika stok di-set 0 oleh admin
                'status' => $request->stock == 0 ? 'empty' : 'ready',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Stok barang berhasil diperbarui! 📦');
    }

    // 3. Fitur Menghapus Angka Stok (Reset Jadi 0)
    public function deleteStock($id)
    {
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