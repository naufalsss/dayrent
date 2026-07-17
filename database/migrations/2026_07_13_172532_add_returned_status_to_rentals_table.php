<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan Statement SQL Mentah karena merubah struktur ENUM di Laravel paling aman lewat DB::statement
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'approved', 'declined', 'expired', 'returned') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika di-rollback, kembalikan ke struktur enum awal (pastikan tidak ada data 'returned' saat rollback dilakukan)
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'approved', 'declined', 'expired') NOT NULL DEFAULT 'pending'");
    }
};