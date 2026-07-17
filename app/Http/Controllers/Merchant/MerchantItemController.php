<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MerchantItemController extends Controller
{
    // GANTI METHOD index() DI DALAM MerchantItemController.php LU MENJADI SEPERTI INI BREE:
    
    public function index(Request $request)
    {
        $merchantId = auth()->id();
        
        // Tangkap inputan filter dari view
        $keyword = $request->get('keyword');
        $categoryId = $request->get('category_id');

        // 1. Bangun Query Utama dengan Filter Dinamis
        $query = DB::table('items')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->where('items.user_id', $merchantId)
            ->select('items.*', 'categories.name as category_name');

        // Filter berdasarkan kata kunci nama jika diisi
        if (!empty($keyword)) {
            $query->where('items.name', 'LIKE', '%' . $keyword . '%');
        }

        // Filter berdasarkan kategori jika dipilih
        if (!empty($categoryId)) {
            $query->where('items.category_id', $categoryId);
        }

        // Ambil data akhir produk setelah difilter
        $items = $query->orderBy('items.created_at', 'desc')->get();

        // 2. Ambil semua kategori untuk kebutuhan dropdown select filter & form tambah
        $categories = DB::table('categories')->get();

        // Lempar data ke view beserta status filter saat ini agar nilainya tidak reset setelah submit
        return view('merchant.items.index', compact('items', 'categories', 'keyword', 'categoryId'));
    }

    // 2. Form Tambah Unit Baru (Ambil Dropdown Kategori dari Admin)
    public function create()
    {
        $categories = DB::table('categories')->orderBy('name', 'asc')->get();
        return view('merchant.items.create', compact('categories'));
    }

    // 3. Simpan Unit Barang Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|string',
            'rent_mode' => 'required|in:hari,bulan',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'features' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        DB::table('items')->insert([
            'user_id' => auth()->id(), // Kunci: Terikat ke merchant yang login
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'rent_mode' => $request->rent_mode,
            'stock' => $request->stock,
            'status' => 'available',
            'image' => $imagePath,
            'features' => $request->features,
            'total_rented' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('merchant.items.index')->with('success', 'Unit barang berhasil ditambahkan ke katalog sewa lu, Bree!');
    }

    // 4. Proses Hapus Unit Barang
    public function destroy($id)
    {
        $merchantId = auth()->id();
        $item = DB::table('items')->where('id', $id)->where('user_id', $merchantId)->first();

        if (!$item) {
            return redirect()->route('merchant.items.index')->with('error', 'Gagal menghapus! Unit barang tidak ditemukan.');
        }

        // Hapus file gambar dari storage publik jika ada
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        DB::table('items')->where('id', $id)->delete();

        return redirect()->route('merchant.items.index')->with('success', 'Unit barang berhasil dihapus dari sistem.');
    }
    
    // Metode placeholder untuk edit jika nanti lu mau buat halamannya
    public function edit($id)
    {
        return "Fitur Form Edit Unit Barang ke-{$id} Comming Soon, Bree!";
    }

    public function update(Request $request, $id)
    {
        return "Fitur Update Unit Barang ke-{$id} Comming Soon, Bree!";
    }
}