<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\Promo;
use Carbon\Carbon; // FIX TAMBAHAN: Wajib panggil Carbon untuk urusan manipulasi waktu

class HomeController extends Controller
{
    public function index()
    {
        // =========================================================================
        //  OTOMATISASI PENGEMBALIAN STOK JIKA WAKTU SEWA HABIS
        // =========================================================================
        // 1. Cari semua transaksi berstatus 'approved' yang waktu expired_at-nya sudah lewat dari waktu sekarang
        $expiredRentals = DB::table('rentals')
            ->where('status', 'approved')
            ->where('expired_at', '<=', Carbon::now())
            ->get();

        foreach ($expiredRentals as $rental) {
            // 2. Kembalikan stok item terkait (tambah 1) ke dalam tabel items
            DB::table('items')->where('id', $rental->item_id)->increment('stock', 1);
            
            // 3. Ubah status rental tersebut menjadi 'declined' atau 'expired' agar tidak diproses berulang kali
            DB::table('rentals')->where('id', $rental->id)->update([
                'status' => 'declined', // Status diubah agar di history user tercatat selesai/berakhir
                'updated_at' => now()
            ]); 
        }
        // =========================================================================

        // Mengambil semua config dan menjadikannya array key => value
        $configs = DB::table('cms_configs')->pluck('value', 'key')->toArray();

        // Hitung statistik default sesuai request-mu
        $stats = [
            'pelanggan' => '150+', 
            'berdiri' => '1 Thn',
            'rating' => '4.9'
        ];

        // Simulasikan data dari tabel 'categories' sesuai proposal universal rental kalian
        $categories = Category::latest()->get();

        // =========================================================================
        // FIX STEP 6: QUERY DINAMIS TOP ITEMS DARI KESELURUHAN MERCHANT
        // =========================================================================
        // 1. Ambil ID Item yang paling banyak disewa dari tabel rentals (status approved & returned)
        $topItemIds = DB::table('rentals')
            ->select('item_id', DB::raw('count(*) as total_sewa'))
            ->whereIn('status', ['approved', 'returned'])
            ->groupBy('item_id')
            ->orderBy('total_sewa', 'desc')
            ->limit(8) // Batasi mengambil 8 item terpopuler untuk halaman depan
            ->pluck('item_id')
            ->toArray();

        // 2. Jika ada data transaksi, ambil item berdasarkan urutan popularitas sewa
        if (!empty($topItemIds)) {
            // Menggunakan field() agar urutan item tetap sama sesuai rank popularitas sewa
            $idsOrder = implode(',', $topItemIds);
            $items = Item::with('category')
                ->whereIn('id', $topItemIds)
                ->orderByRaw("FIELD(id, {$idsOrder})")
                ->get();
        } else {
            // Fallback: Jika web baru dan belum ada transaksi sewa sama sekali, tampilkan barang terbaru
            $items = Item::with('category')->latest()->limit(8)->get();
        }
        // =========================================================================

        // Tarik data promo dinamis untuk slider bawah
        $promos = Promo::latest()->get();

        return view('welcome', compact('configs', 'stats', 'categories', 'items', 'promos'));
    }

    public function checkout($id)
    {
        // Ambil data unit barang riil dari DB beserta kategorinya
        $item = Item::with('category')->findOrFail($id);
        
        // Ambil config aplikasi
        $configs = DB::table('cms_configs')->pluck('value', 'key')->toArray();

        return view('checkout', compact('item', 'configs'));
    }
}