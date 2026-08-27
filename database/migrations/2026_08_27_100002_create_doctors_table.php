<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('specialization');
            $table->string('phone');
            $table->string('email')->unique();
            $table->decimal('consultation_fee', 10, 2)->default(50.00);
            $table->string('available_days')->default('Mon, Tue, Wed, Thu, Fri');
            $table->string('available_time')->default('09:00 AM - 05:00 PM');
            $table->enum('status', ['active', 'on_leave', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
