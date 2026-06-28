<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'latitude',
        'longitude',
        'phone',
        'phone_verified_at',
        'verification_status',
        'suspension_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function taskerProfile(): HasOne
    {
        return $this->hasOne(TaskerProfile::class);
    }

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'tasker_specializations', 'user_id', 'specialization_id');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'tasker_languages', 'user_id', 'language_id');
    }

    public function jobRequests(): HasMany
    {
        return $this->hasMany(JobRequest::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function receivedRatings(): HasMany
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'user_id', 'tasker_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(TaskerReport::class, 'reported_tasker_id');
    }

    public function availability(): HasOne
    {
        return $this->hasOne(TaskerAvailability::class);
    }

    public function scopeWithRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at')->where('verification_status', 'verified');
    }

    public function scopeNotSuspended($query)
    {
        return $query->where('suspension_status', 'none');
    }

    public function scopeWithinRadius($query, $latitude, $longitude, $radiusKm)
    {
        $earthRadiusKm = 6371;
        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);

        return $query->selectRaw("users.*,
            (
                {$earthRadiusKm} *
                acos(
                    cos(?) *
                    cos(RADIANS(latitude)) *
                    cos(RADIANS(longitude) - ?) +
                    sin(?) *
                    sin(RADIANS(latitude))
                )
            ) AS distance", [
            $latFrom,
            deg2rad($longitude),
            $latFrom,
        ])->having('distance', '<=', $radiusKm)->orderBy('distance');
    }
}
