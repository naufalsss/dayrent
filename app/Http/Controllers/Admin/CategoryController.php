<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // 1. Tampilkan Semua Kategori (Sudah Aman)
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    // 2. Simpan Kategori Baru (BERSIH DARI ICON)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            // Kolom icon resmi dihapus total dari sini!
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan! ✅');
    }

    // 3. Update Data Kategori (BERSIH DARI ICON)
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            // Kolom icon resmi dihapus total dari sini!
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. Hapus Kategori (Sudah Aman)
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus dari sistem!');
    }
}