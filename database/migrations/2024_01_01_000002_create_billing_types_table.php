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
        Schema::create('billing_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Air PAM, Kebersihan, Sampah, Security
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('default_amount', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_types');
    }
};
