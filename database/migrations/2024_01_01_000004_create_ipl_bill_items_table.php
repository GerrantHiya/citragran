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
        Schema::create('ipl_bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipl_bill_id')->constrained()->onDelete('cascade');
            $table->foreignId('billing_type_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2)->default(0);
            $table->integer('meter_previous')->nullable(); // For water meter
            $table->integer('meter_current')->nullable(); // For water meter
            $table->integer('usage')->nullable(); // For water meter
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipl_bill_items');
    }
};
