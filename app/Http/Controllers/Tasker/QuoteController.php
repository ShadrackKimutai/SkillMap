<?php

namespace App\Http\Controllers\Tasker;

use App\Models\Quote;
use App\Models\JobRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{
    public function index(): View
    {
        $quotes = auth()->user()->quotes()->with('jobRequest')->latest()->paginate(15);
        return view('tasker.quotes.index', compact('quotes'));
    }

    public function create(JobRequest $jobRequest): View
    {
        return view('tasker.quotes.create', compact('jobRequest'));
    }

    public function store(Request $request, JobRequest $jobRequest): RedirectResponse
    {
        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'message' => ['nullable', 'string'],
        ]);

        $tasker = auth()->user();
        $distance = 0;

        if ($tasker->latitude && $jobRequest->latitude) {
            $earthRadiusKm = 6371;
            $latFrom = deg2rad($tasker->latitude);
            $lonFrom = deg2rad($tasker->longitude);
            $latTo = deg2rad($jobRequest->latitude);
            $lonTo = deg2rad($jobRequest->longitude);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                 cos($latFrom) * cos($latTo) *
                 sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = round($earthRadiusKm * $c, 2);
        }

        Quote::create([
            'job_request_id' => $jobRequest->id,
            'user_id' => auth()->id(),
            'job_location' => $jobRequest->location,
            'job_latitude' => $jobRequest->latitude,
            'job_longitude' => $jobRequest->longitude,
            'distance_km' => $distance,
            ...$validated,
        ]);

        return redirect()->route('tasker.quotes.index')->with('success', 'Quote sent');
    }

    public function show(Quote $quote): View
    {
        $this->authorize('view', $quote);
        return view('tasker.quotes.show', compact('quote'));
    }
}
