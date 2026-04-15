<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Who made the booking — one will be set, the other null
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('passenger_id')->nullable()->constrained('passengers')->nullOnDelete();

            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained()->cascadeOnDelete();

            // Flat passenger details — stored at time of booking
            $table->string('passenger_name');
            $table->string('passenger_email');
            $table->string('passenger_phone');
            $table->string('passenger_nic')->nullable();

            $table->decimal('amount', 8, 2);
            $table->string('stripe_payment_intent_id')->nullable();
            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending');
            $table->string('booking_ref')->unique(); // SLTB-2026-00001
            $table->timestamp('booked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};