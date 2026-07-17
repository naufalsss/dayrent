<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kita gunakan query mentah (RAW SQL) agar modifikasi ENUM di MySQL berjalan 100% aman dan presisi
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'approved', 'declined', 'expired') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan ke struktur awal jika migrasi di-rollback
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'approved', 'declined') NOT NULL DEFAULT 'pending'");
    }
};