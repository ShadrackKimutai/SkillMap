<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icon'];

    public function specializations(): HasMany
    {
        return $this->hasMany(Specialization::class);
    }

    public function taskerProfiles(): HasMany
    {
        return $this->hasMany(TaskerProfile::class);
    }

    public function jobRequests(): HasMany
    {
        return $this->hasMany(JobRequest::class);
    }
}
