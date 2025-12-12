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
        // Tambah kolom luas tanah dan tarif IPL ke residents
        Schema::table('residents', function (Blueprint $table) {
            $table->decimal('land_area', 10, 2)->nullable()->after('block_number'); // Luas tanah dalam m²
            $table->decimal('ipl_amount', 12, 2)->default(0)->after('land_area'); // Tarif IPL bulanan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['land_area', 'ipl_amount']);
        });
    }
};
