<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_request_id',
        'user_id',
        'price',
        'estimated_hours',
        'message',
        'status',
        'job_location',
        'job_latitude',
        'job_longitude',
        'distance_km',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'estimated_hours' => 'decimal:2',
    ];

    public function jobRequest(): BelongsTo
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function tasker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class, 'job_request_id', 'job_request_id');
    }

    public function calculateDistance(): float
    {
        if (!$this->tasker->latitude || !$this->job_latitude) {
            return 0;
        }

        $earthRadiusKm = 6371;
        $latFrom = deg2rad($this->tasker->latitude);
        $lonFrom = deg2rad($this->tasker->longitude);
        $latTo = deg2rad($this->job_latitude);
        $lonTo = deg2rad($this->job_longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadiusKm * $c, 2);
    }
}
