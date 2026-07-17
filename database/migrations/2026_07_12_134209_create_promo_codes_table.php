<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: DISKONMANTAP
            $table->enum('type', ['percentage', 'nominal']); // Persen (%) atau Potongan Rupiah (Rp)
            $table->decimal('reward_value', 15, 2); // Nilai potongan (misal: 10 untuk 10%, atau 50000 untuk Rp50.000)
            $table->integer('max_uses')->default(100); // Batas kuota pemakaian kupon
            $table->integer('total_used')->default(0); // Jumlah kupon yang sudah terpakai
            $table->timestamp('expired_at')->nullable(); // Masa kedaluwarsa kupon
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};