<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // <-- Tetap pastikan ini di-import ya!

class DashboardController extends Controller
{
    // Fungsi menampilkan halaman utama dashboard admin (UPDATE GRAFIK DAN DATA DINAMIS)
    public function index(Request $request)
    {
        // =========================================================================
        // 1. DATA CARD STATUS UTAMA
        // =========================================================================
        $totalKategori = DB::table('categories')->count();
        $totalBarang = DB::table('items')->count();
        $penyewaanAktif = DB::table('rentals')->where('status', 'approved')->count();
        
        // FIX SAKTI: Mengambil nominal bersih setelah diskon langsung dari kolom total_price
        $totalPendapatan = DB::table('rentals')
            ->where('status', 'approved')
            ->sum('total_price');

        // =========================================================================
        // 2. GRAFIK 1: ITEM PALING POPULER (30, 60, 90, 180, 360 Hari)
        // =========================================================================
        // Ambil input filter dropdown 'days' (Default fallback 30 hari jika kosong)
        $daysFilter = $request->get('days', 30);
        
        $popularItemsData = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('items.name', DB::raw('COUNT(rentals.id) as total_rented'))
            ->where('rentals.status', 'approved')
            ->where('rentals.created_at', '>=', now()->subDays($daysFilter))
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_rented', 'desc')
            ->limit(5) // Batasi top 5 item saja biar grafik tidak penuh sesak
            ->get();

        $popularItemLabels = $popularItemsData->pluck('name')->toArray();
        $popularItemValues = $popularItemsData->pluck('total_rented')->toArray();

        // =========================================================================
        // 3. GRAFIK 2: PENDAPATAN BULANAN PER TAHUN DINAMIS
        // =========================================================================
        // Cari daftar tahun unik yang pernah ada transaksi disetujui di DB
        $availableYears = DB::table('rentals')
            ->where('status', 'approved')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Jika DB baru pertama running & masih kosong, buat fallback tahun berjalan
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        // Ambil input filter tahun berjalan (Default tahun sekarang)
        $yearFilter = $request->get('year', date('Y'));

        // FIX SAKTI: Kalkulasi total bulanan berdasarkan kolom total_price (Setelah potongan diskon)
        $monthlyEarningsData = DB::table('rentals')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'approved')
            ->whereYear('created_at', $yearFilter)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        // Mapping index array 1-12 (Jan-Des) agar tidak ada bulan melompat di Chart.js
        $monthlyEarnings = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyEarnings[] = $monthlyEarningsData[$m] ?? 0;
        }

        // Kirim seluruh payload variabel ke view dashboard admin
        return view('admin.dashboard', compact(
            'totalKategori', 'totalBarang', 'penyewaanAktif', 'totalPendapatan',
            'popularItemLabels', 'popularItemValues', 'daysFilter',
            'monthlyEarnings', 'availableYears', 'yearFilter'
        ));
    }

    // Fungsi menampilkan halaman pengaturan CMS
    public function cmsSettings()
    {
        // Ambil konfigurasi saat ini dari database tabel 'cms_configs'
        $configs = DB::table('cms_configs')->pluck('value', 'key')->toArray();
        
        return view('admin.cms_settings', compact('configs'));
    }

    // Fungsi menyimpan perubahan teks CMS dari form + FITUR UPLOAD FILE HERO BACKGROUND & LOGO APLIKASI
    public function updateCms(Request $request)
    {
        // 1. Validasi Inputan Teks & File Gambar (Tambahkan validasi app_logo)
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'hero_button_text' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'slider_bg_color_start' => 'nullable|string|max:7',
            'slider_bg_color_end' => 'nullable|string|max:7',
            'hero_bg_image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // <-- VALIDASI LOGO BARU (Max 2MB)
        ]);

        // 2. LOGIC KHUSUS FILE GAMBAR HERO BACKGROUND
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

        // 2.5 LOGIC KHUSUS UPLOAD LOGO APLIKASI BARU
        if ($request->hasFile('app_logo')) {
            // Ambil nama file logo lama jika ada, lalu hapus dari storage biar ga penuh
            $oldLogo = DB::table('cms_configs')->where('key', 'app_logo')->value('value');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Simpan logo baru ke dalam folder storage/app/public/cms
            $logoPath = $request->file('app_logo')->store('cms', 'public');

            // Masukkan path logo baru ke database cms_configs
            $logoPath = $request->file('app_logo')->store('cms', 'public');
            DB::table('cms_configs')->updateOrInsert(
                ['key' => 'app_logo'],
                ['value' => $logoPath]
            );
        }

        // 3. FIX SAKTI: Ambil semua data teks kecuali token dan kedua file gambar
        // Tambahkan 'app_logo' ke dalam array agar tidak ikut masuk ke loop teks di bawah
        $data = $request->except(['_token', 'hero_bg_image', 'app_logo']);

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

        return redirect()->back()->with('success', 'Konfirmasi: Pengaturan CMS & Logo Berhasil Diperbarui! 🎨');
    }
}