<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quote;

class QuotePolicy
{
    public function view(User $user, Quote $quote): bool
    {
        return $user->id === $quote->user_id || $user->id === $quote->jobRequest->user_id;
    }

    public function accept(User $user, Quote $quote): bool
    {
        return $user->id === $quote->jobRequest->user_id && $quote->status === 'pending';
    }

    public function reject(User $user, Quote $quote): bool
    {
        return $user->id === $quote->jobRequest->user_id && $quote->status === 'pending';
    }
}
