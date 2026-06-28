<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaskerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trade_id',
        'hourly_rate',
        'fixed_rate',
        'average_rating',
        'rating_count',
        'price_negotiable',
        'bio',
        'is_promoted',
    ];

    protected $casts = [
        'price_negotiable' => 'boolean',
        'is_promoted' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function specializations(): BelongsToMany
    {
        return $this->user->specializations();
    }

    public function languages(): BelongsToMany
    {
        return $this->user->languages();
    }

    public function availability(): HasOne
    {
        return $this->user->availability();
    }

    public function quotes(): HasMany
    {
        return $this->user->quotes();
    }

    public function reports(): HasMany
    {
        return $this->user->reports();
    }
}
