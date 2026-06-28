<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskerAvailability extends Model
{
    use HasFactory;

    protected $table = 'tasker_availability';

    protected $fillable = [
        'user_id',
        'status',
        'weekly_hours_start',
        'weekly_hours_end',
    ];

    protected $casts = [
        'weekly_hours_start' => 'time',
        'weekly_hours_end' => 'time',
    ];

    public function tasker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
