<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // <-- WAJIB TAMBAHKAN IMPORT INI BREE!

class DashboardController extends Controller
{
    // Fungsi menampilkan halaman utama dashboard admin
    public function index()
    {
        // Mengambil statistik riil / hitungan dummy untuk dashboard
        $totalKategori = count([1,2,3,4,5,6]); // Nanti diganti Category::count()
        $totalBarang = count([1,2,3,4,5,6]);   // Nanti diganti Item::count()
        
        return view('admin.dashboard', compact('totalKategori', 'totalBarang'));
    }

    // Fungsi menampilkan halaman pengaturan CMS
    public function cmsSettings()
    {
        // Ambil konfigurasi saat ini dari database tabel 'cms_configs'
        $configs = DB::table('cms_configs')->pluck('value', 'key')->toArray();
        
        return view('admin.cms_settings', compact('configs'));
    }

    // Fungsi menyimpan perubahan teks CMS dari form + FITUR UPLOAD FILE HERO BACKGROUND
    public function updateCms(Request $request)
    {
        // 1. Validasi Inputan Teks & File Gambar (Max 3MB)
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'hero_button_text' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'slider_bg_color_start' => 'nullable|string|max:7',
            'slider_bg_color_end' => 'nullable|string|max:7',
            'hero_bg_image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        // 2. LOGIC KHUSUS FILE GAMBAR HERO BACKGROUND (Hanya diproses jika ada file baru yang di-upload)
        if ($request->hasFile('hero_bg_image')) {
            $oldBgImage = DB::table('cms_configs')->where('key', 'hero_bg_image')->value('value');
            if ($oldBgImage && Storage::disk('public')->exists($oldBgImage)) {
                Storage::disk('public')->delete($oldBgImage);
            }

            $path = $request->file('hero_bg_image')->store('cms', 'public');

            DB::table('cms_configs')->updateOrInsert(
                ['key' => 'hero_bg_image'],
                ['value' => $path]
            );
        }

        // 3. FIX SAKTI: Ambil semua data teks kecuali token dan file gambar
        $data = $request->except(['_token', 'hero_bg_image']);

        foreach ($data as $key => $value) {
            // HANYA UPDATE JIKA YANG DIINPUT ADMIN TIDAK KOSONG ATAU TIDAK NULL
            if (!is_null($value) && $value !== '') {
                DB::table('cms_configs')
                    ->updateOrInsert(
                        ['key' => $key],
                        ['value' => $value]
                    );
            }
        }

        return redirect()->back()->with('success', 'Konfirmasi: Pengaturan CMS Berhasil Diperbarui secara Parsial! 🎨');
    }
}