<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trade_id',
        'title',
        'description',
        'budget',
        'date_needed',
        'status',
        'location',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'date_needed' => 'datetime',
        'budget' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function acceptedQuote(): HasOne
    {
        return $this->hasOne(Quote::class)->where('status', 'accepted');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function chatThread(): HasOne
    {
        return $this->hasOne(ChatThread::class);
    }
}
