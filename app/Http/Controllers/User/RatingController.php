<?php

namespace App\Http\Controllers\User;

use App\Models\Rating;
use App\Models\JobRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
    public function create(JobRequest $jobRequest): View
    {
        $this->authorize('rate', $jobRequest);
        $quote = $jobRequest->acceptedQuote;
        return view('user.ratings.create', compact('jobRequest', 'quote'));
    }

    public function store(Request $request, JobRequest $jobRequest): RedirectResponse
    {
        $this->authorize('rate', $jobRequest);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['required', 'string', 'min:10'],
        ]);

        $quote = $jobRequest->acceptedQuote;

        Rating::create([
            'job_request_id' => $jobRequest->id,
            'rater_id' => auth()->id(),
            'rated_user_id' => $quote->user_id,
            ...$validated,
        ]);

        $jobRequest->update(['status' => 'completed']);

        return redirect()->route('user.dashboard')->with('success', 'Rating submitted');
    }
}
