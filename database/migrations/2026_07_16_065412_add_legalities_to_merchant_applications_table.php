<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchant_applications', function (Blueprint $table) {
            // 1. Identitas Pribadi
            $table->string('ktp_number')->nullable();
            $table->string('npwp_personal')->nullable();
            
            // 2. Legalitas Badan Usaha
            $table->enum('business_type', ['individual', 'company'])->default('individual');
            $table->string('nib_number')->nullable();
            $table->string('akta_number')->nullable();
            $table->string('npwp_business')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('merchant_applications', function (Blueprint $table) {
            $table->dropColumn(['ktp_number', 'npwp_personal', 'business_type', 'nib_number', 'akta_number', 'npwp_business']);
        });
    }
};