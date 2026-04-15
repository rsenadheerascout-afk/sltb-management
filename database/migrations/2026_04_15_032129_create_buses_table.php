<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_no')->unique();
            $table->string('depot_reg_no')->unique(); // Format: YTXXX
            $table->string('brand');
            $table->integer('seat_count');
            $table->integer('manufactured_year')->nullable();
            $table->enum('status', [
                'active',
                'needs_repair',
                'under_repair',
                'maintenance',
                'condemned'
            ])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
