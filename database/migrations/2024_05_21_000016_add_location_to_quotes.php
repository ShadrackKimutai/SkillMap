<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('job_location')->nullable()->after('message');
            $table->decimal('job_latitude', 10, 8)->nullable()->after('job_location');
            $table->decimal('job_longitude', 10, 8)->nullable()->after('job_latitude');
            $table->decimal('distance_km', 8, 2)->nullable()->after('job_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['job_location', 'job_latitude', 'job_longitude', 'distance_km']);
        });
    }
};
