<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobRequest;

class JobRequestPolicy
{
    public function view(User $user, JobRequest $jobRequest): bool
    {
        return $user->id === $jobRequest->user_id;
    }

    public function rate(User $user, JobRequest $jobRequest): bool
    {
        return $user->id === $jobRequest->user_id && $jobRequest->status === 'completed';
    }
}
