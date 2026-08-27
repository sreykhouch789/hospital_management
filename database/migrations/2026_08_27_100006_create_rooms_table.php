<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->enum('type', ['General Ward', 'Private Room', 'Semi-Private', 'ICU', 'Emergency']);
            $table->enum('status', ['Available', 'Occupied', 'Maintenance'])->default('Available');
            $table->decimal('daily_rate', 10, 2);
            $table->foreignId('patient_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
