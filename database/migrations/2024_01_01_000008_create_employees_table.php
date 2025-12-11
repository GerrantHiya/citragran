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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->enum('type', ['security', 'cleaning', 'garbage', 'technical']);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('id_number')->nullable(); // KTP
            $table->date('birth_date')->nullable();
            $table->date('join_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('salary_type', ['daily', 'weekly', 'monthly'])->default('monthly');
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
