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
        // Tambah kolom luas tanah ke residents
        Schema::table('residents', function (Blueprint $table) {
            $table->decimal('land_area', 10, 2)->nullable()->after('block_number'); // Luas tanah dalam m²
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('land_area');
        });
    }
};
