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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // Menghubungkan id kategori ke tabel categories (Foreign Key)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('price'); // Kita simpan string dulu biar gampang, misal: 2.500.000
            $table->string('image'); // Menyimpan URL gambar jepretan/Unsplash
            $table->string('rating')->default('5.0');
            $table->string('features'); // Menyimpan fitur, kita simpan format string pisahan koma
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
