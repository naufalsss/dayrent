<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. FIX SAKTI: NYALAKAN KEMBALI! Kolom wajib dibuat dulu karena migrate:fresh itu membangun dari nol!
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('order_code')->nullable()->after('id');
        });

        // 2. TETAP JALAN: Isi data transaksi lama jika ada (kalau migrate:fresh ini akan otomatis dilewati karena datanya pasti kosong)
        $rentals = DB::table('rentals')->whereNull('order_code')->orWhere('order_code', '')->get();
        foreach ($rentals as $rental) {
            $randomCode = 'DR-' . date('Ymd', strtotime($rental->created_at)) . '-' . strtoupper(Str::random(4));
            DB::table('rentals')->where('id', $rental->id)->update(['order_code' => $randomCode]);
        }

        // 3. TETAP JALAN: Mengubah kolom menjadi UNIQUE secara resmi setelah dipastikan semua baris memiliki isi
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('order_code')->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('order_code');
        });
    }
};