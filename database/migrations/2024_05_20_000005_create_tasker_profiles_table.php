<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('trade_id')->constrained('trades')->onDelete('cascade');
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('fixed_rate', 10, 2)->nullable();
            $table->boolean('price_negotiable')->default(false);
            $table->text('bio')->nullable();
            $table->integer('rating_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->boolean('is_promoted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasker_profiles');
    }
};
