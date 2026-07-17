<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- TAMBAHKAN IMPORT INI BREE!

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // =========================================================================
        // LOGIKA SAKTI: ENGINE AUTO-EXPIRED PENYEWAAN REAL-TIME
        // Otomatis mengubah status 'approved' menjadi 'expired' jika jam/tanggal
        // sudah melewati waktu saat ini (now).
        // =========================================================================
        DB::table('rentals')
            ->where('expired_at', '<', Carbon::now())
            ->where('status', '=', 'approved')
            ->update(['status' => 'expired']);

        // 1. Ambil input pencarian dan filter kategori dari URL Query
        $search = $request->get('search');
        $categorySlug = $request->get('category', 'all');

        // 2. Ambil semua kategori untuk menu filter sidebar/topbar
        $categories = DB::table('categories')->get();

        // 3. Bangun query dasar penarikan item barang + join dengan kategori
        $itemsQuery = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'categories.name as category_name', 'categories.slug as category_slug');

        // Filter A: Jika admin/user mengetik keyword pencarian
        if (!empty($search)) {
            $itemsQuery->where('items.name', 'LIKE', '%' . $search . '%');
        }

        // Filter B: Jika user memilih kategori spesifik selain 'all'
        if ($categorySlug !== 'all') {
            $itemsQuery->where('categories.slug', $categorySlug);
        }

        // Eksekusi data item barang
        $items = $itemsQuery->orderBy('items.created_at', 'desc')->get();

        // Ambil data CMS Config untuk logo dan nama aplikasi di layout
        $configs = DB::table('cms_configs')->pluck('value', 'key')->toArray();

        return view('catalog', compact('items', 'categories', 'search', 'categorySlug', 'configs'));
    }
}