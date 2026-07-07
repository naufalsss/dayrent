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
        // FIX BERSIH: Gunakan Schema::, BUKAN Route::
        Schema::table('promos', function (Blueprint $table) {
            $table->string('image')->nullable()->after('link_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FIX BERSIH: Gunakan Schema::, BUKAN Route::
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
