<?php

namespace App\Http\Controllers\User;

use App\Models\JobRequest;
use App\Models\Quote;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $stats = [
            'total_jobs' => $user->jobRequests()->count(),
            'active_jobs' => $user->jobRequests()->where('status', 'open')->count(),
            'favorites' => $user->favorites()->count(),
        ];

        $recentJobs = $user->jobRequests()->latest()->limit(5)->get();
        $pendingQuotes = Quote::whereHas('jobRequest', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentJobs', 'pendingQuotes'));
    }
}
