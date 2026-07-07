<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('whatsapp_number');
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps(); // Ini otomatis merekam tanggal & waktu (created_at)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};