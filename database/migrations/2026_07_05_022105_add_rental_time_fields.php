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
        // Tambah mode sewa di tabel items (default 'hari')
        Schema::table('items', function (Blueprint $table) {
            $table->enum('rent_mode', ['hari', 'bulan'])->default('hari')->after('price');
        });

        // Tambah catatan durasi dan tanggal kedaluwarsa di tabel rentals
        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('duration')->default(1)->after('whatsapp_number');
            $table->timestamp('expired_at')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('rent_mode');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['duration', 'expired_at']);
        });
    }
};
