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

        // Simulasikan data dari tabel 'items' (Objek Rental Universal)
        $items = Item::with('category')->latest()->get();

        // Tarik data promo dinamis untuk slider bawah
        $promos = Promo::latest()->get();

        return view('welcome', compact('configs', 'stats', 'categories', 'items', 'promos'));
    }

    public function checkout($id)
    {
        // Ambil data unit barang riil dari DB beserta kategorinya
        $item = Item::with('category')->findOrFail($id);
        
        // Ambil config aplikasi
        $configs = \DB::table('cms_configs')->pluck('value', 'key')->toArray();

        return view('checkout', compact('item', 'configs'));
    }
}