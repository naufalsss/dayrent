<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // FIX UTAMA: Gunakan Schema::table, bukan Route::with
        Schema::table('items', function (Blueprint $table) {
            // Menambahkan kolom stock berupa integer, default 0, ditaruh setelah kolom price
            $table->integer('stock')->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FIX UTAMA: Gunakan Schema::table, bukan Route::with
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};