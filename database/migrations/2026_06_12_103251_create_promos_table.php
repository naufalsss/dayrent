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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('tag'); // Misal: Universal Promo, Keamanan, Layanan
            $table->string('title'); // Kalimat utama promo/fitur
            $table->string('link_text'); // Teks tombol tautan, misal: "Jelajahi Katalog"
            $table->string('link_url')->default('#'); // Alamat tujuan klik tombol
            $table->string('badge_color')->default('blue'); // Opsi warna: blue, purple, emerald, amber, rose
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
