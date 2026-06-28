<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasker_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_tasker_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->enum('reason', ['fake', 'spam', 'fraud', 'harassment', 'wrong_skills', 'other']);
            $table->text('description')->nullable();
            $table->enum('admin_action', ['dismissed', 'warned', 'suspended_7d', 'suspended_30d', 'banned'])->nullable();
            $table->timestamp('action_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasker_reports');
    }
};
