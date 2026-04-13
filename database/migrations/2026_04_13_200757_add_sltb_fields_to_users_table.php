<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('employee')->after('password');
            $table->string('nic')->nullable()->after('role');
            $table->string('phone')->nullable()->after('nic');
            $table->string('address')->nullable()->after('phone');
            $table->string('employee_id')->nullable()->unique()->after('address');
            $table->enum('status', ['active', 'disabled'])->default('active')->after('employee_id');
            $table->boolean('is_approved')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'nic', 'phone', 'address',
                'employee_id', 'status', 'is_approved',
            ]);
        });
    }
};