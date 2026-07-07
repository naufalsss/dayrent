<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Tampilkan List Barang sekaligus Form Tambah data (Split View)
    public function index()
    {
        $items = Item::with('category')->latest()->get();
        $categories = Category::all(); 
        return view('admin.items.index', compact('items', 'categories'));
    }

    // Simpan data barang baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'features' => 'required|string',
        ]);

        $inputData = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $inputData['image'] = '/storage/' . 'products/' . $filename;
        }

        Item::create($inputData);

        return redirect()->back()->with('success', 'Unit barang rental baru sukses didaftarkan dengan gambar lokal!');
    }

    // ================= FIX SAKTI: SEKARANG FUNGSI UPDATE SUDAH ADA =================
    public function update(Request $request, Item $item)
    {
        // 1. Validasi data edit (Image dibuat nullable karena tidak wajib ganti foto tiap edit)
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'features' => 'required|string',
        ]);

        // 2. Ambil data teks form
        $inputData = $request->only(['category_id', 'name', 'price', 'features']);

        // 3. Jika admin mengupload foto baru, proses ganti fotonya
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $inputData['image'] = '/storage/' . 'products/' . $filename;
        }

        // 4. Eksekusi update ke database
        $item->update($inputData);

        return redirect()->back()->with('success', 'Data unit sewa berhasil diperbarui! ✅');
    }

    // Hapus barang dari database
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Unit barang sukses dihapus dari katalog!');
    }
}