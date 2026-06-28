<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = ['trade_id', 'name', 'description'];

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function taskers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tasker_specializations', 'specialization_id', 'user_id');
    }
}
