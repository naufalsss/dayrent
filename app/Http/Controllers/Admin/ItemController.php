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
        // FIX AMAN: Mengambil barang milik admin sendiri ATAU barang bawaan yang user_id-nya kosong (NULL)
        $items = Item::with('category')
            ->where(function($query) {
                $query->where('user_id', auth()->id())
                      ->orWhereNull('user_id');
            })
            ->latest()
            ->get();
            
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

        // FIX: Otomatis kunci user_id ke ID Super Admin yang membuat barang ini
        $inputData['user_id'] = auth()->id();

        Item::create($inputData);

        return redirect()->back()->with('success', 'Unit barang rental baru sukses didaftarkan dengan gambar lokal!');
    }

    // FIX SAKTI: SEKARANG FUNGSI UPDATE SUDAH ADA & DIPROTEKSI
    public function update(Request $request, Item $item)
    {
        // PROTEKSI: Mencegah Admin usil mengubah unit milik merchant lain via manipulasi inspect element / API
        if ($item->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Oops! Anda tidak berhak mengubah unit barang ini.');
        }

        // 1. Validasi data edit
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
        // PROTEKSI: Mencegah Admin menghapus barang milik merchant lain
        if ($item->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Oops! Anda tidak berhak menghapus unit barang ini.');
        }

        $item->delete();
        return redirect()->back()->with('success', 'Unit barang sukses dihapus dari katalog!');
    }
}