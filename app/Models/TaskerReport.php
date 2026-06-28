<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskerReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_tasker_id',
        'reporter_id',
        'reason',
        'description',
        'admin_action',
        'action_date',
    ];

    protected $casts = [
        'action_date' => 'datetime',
    ];

    public function reportedTasker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_tasker_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
