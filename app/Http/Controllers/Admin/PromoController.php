<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- WAJIB IMPORT INI BREE

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
        $request->validate([
            'tag' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'badge_color' => 'required|string',
            'link_text' => 'required|string|max:255',
            'item_id' => 'required|exists:items,id',
            'background_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $item = Item::findOrFail($request->item_id);

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
            'link_url' => '/items/' . $item->id . '/checkout',
            'image' => $imagePath, // <-- Simpan path foto ke database (sesuaikan nama kolom tabel lu, misal 'image' atau 'background_image')
        ]);

        return redirect()->back()->with('success', 'Promo Slider dengan background custom berhasil didaftarkan! 📸');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tag' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'badge_color' => 'required|string',
            'link_text' => 'required|string|max:255',
            'item_id' => 'required|exists:items,id',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable pas edit
        ]);

        $promo = Promo::findOrFail($id);
        $item = Item::findOrFail($request->item_id);

        $imagePath = $promo->image; // Pakai foto lama dulu

        // Jika admin upload foto baru pas edit
        if ($request->hasFile('background_image')) {
            // Hapus foto lama dari storage biar gak numpuk sampah
            if ($promo->image && Storage::disk('public')->exists($promo->image)) {
                Storage::disk('public')->delete($promo->image);
            }
            // Simpan foto baru
            $imagePath = $request->file('background_image')->store('promos', 'public');
        }

        $promo->update([
            'tag' => $request->tag,
            'title' => $request->title,
            'badge_color' => $request->badge_color,
            'link_text' => $request->link_text,
            'link_url' => '/items/' . $item->id . '/checkout', 
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Data & Background promo berhasil diperbarui! ✏️');
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);

        // Hapus file foto dari storage sebelum datanya dihapus dari DB
        if ($promo->image && Storage::disk('public')->exists($promo->image)) {
            Storage::disk('public')->delete($promo->image);
        }

        $promo->delete();
        return redirect()->back()->with('success', 'Promo beserta file gambar berhasil dihapus!');
    }
}