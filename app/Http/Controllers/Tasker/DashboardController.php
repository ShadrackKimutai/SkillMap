<?php

namespace App\Http\Controllers\Tasker;

use App\Models\Quote;
use App\Models\Rating;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tasker = auth()->user();
        $stats = [
            'total_quotes' => $tasker->quotes()->count(),
            'pending_quotes' => $tasker->quotes()->where('status', 'pending')->count(),
            'average_rating' => $tasker->receivedRatings()->avg('rating') ?? 0,
            'total_ratings' => $tasker->receivedRatings()->count(),
        ];

        $recentQuotes = $tasker->quotes()->latest()->limit(5)->get();

        return view('tasker.dashboard', compact('stats', 'recentQuotes'));
    }
}
