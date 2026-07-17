<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->get();
        $items = Item::with('category')->latest()->get();
        $categories = Category::all();

        return view('admin.promos.index', compact('promos', 'items', 'categories'));
    }

    public function store(Request $request)
    {
        // FIX: Hapus kewajiban item_id, ganti dengan validasi link_url yang dikirim front-end
        $request->validate([
            'tag' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'badge_color' => 'required|string',
            'link_text' => 'required|string|max:255',
            'link_url' => 'required|string|max:255', // <-- Membaca dynamic link hasil racikan JS
            'background_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Logic Upload Foto Background
        $imagePath = null;
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('promos', 'public');
        }
        
        Promo::create([
            'tag' => $request->tag,
            'title' => $request->title,
            'badge_color' => $request->badge_color,
            'link_text' => $request->link_text,
            'link_url' => $request->link_url, // <-- FIX: Mengambil nilai murni dari front-end
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Promo Slider dengan background custom berhasil didaftarkan! 📸');
    }

    public function update(Request $request, $id)
    {
        // FIX: Longgarkan item_id agar link kategori bisa disimpan tanpa hambatan
        $request->validate([
            'tag' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'badge_color' => 'required|string',
            'link_text' => 'required|string|max:255',
            'link_url' => 'required|string|max:255', // <-- FIX: Validasi dynamic link target
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $promo = Promo::findOrFail($id);
        $imagePath = $promo->image;

        if ($request->hasFile('background_image')) {
            if ($promo->image && Storage::disk('public')->exists($promo->image)) {
                Storage::disk('public')->delete($promo->image);
            }
            $imagePath = $request->file('background_image')->store('promos', 'public');
        }

        $promo->update([
            'tag' => $request->tag,
            'title' => $request->title,
            'badge_color' => $request->badge_color,
            'link_text' => $request->link_text,
            'link_url' => $request->link_url, // <-- FIX: Mengupdate sesuai link_url pilihan terupdate
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Data & Background promo berhasil diperbarui! ✏️');
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);

        if ($promo->image && Storage::disk('public')->exists($promo->image)) {
            Storage::disk('public')->delete($promo->image);
        }

        $promo->delete();
        return redirect()->back()->with('success', 'Promo beserta file gambar berhasil dihapus!');
    }
}