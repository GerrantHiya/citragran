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
        Schema::create('ipl_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kategori (misal: Tipe 36, Tipe 45, dst)
            $table->decimal('min_land_area', 10, 2); // Luas tanah minimum
            $table->decimal('max_land_area', 10, 2)->nullable(); // Luas tanah maksimum (null = tak terbatas)
            $table->decimal('ipl_amount', 12, 2); // Besaran IPL bulanan
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipl_rates');
    }
};
