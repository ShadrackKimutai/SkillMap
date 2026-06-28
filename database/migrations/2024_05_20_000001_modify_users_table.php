<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'tasker', 'user'])->default('user')->after('password');
            $table->decimal('latitude', 10, 8)->nullable()->after('role');
            $table->decimal('longitude', 10, 8)->nullable()->after('latitude');
            $table->string('phone')->nullable()->after('longitude');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('phone_verified_at');
            $table->enum('suspension_status', ['none', 'warning', 'suspended', 'banned'])->default('none')->after('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'latitude', 'longitude', 'phone', 'phone_verified_at', 'verification_status', 'suspension_status']);
        });
    }
};
