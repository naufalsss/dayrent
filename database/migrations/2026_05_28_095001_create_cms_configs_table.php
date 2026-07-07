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
        Schema::create('cms_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique(); // Contoh: 'app_name', 'hero_title'
            $table->text('value')->nullable();   // Isi teks, link, atau nama file gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_configs');
    }
};
