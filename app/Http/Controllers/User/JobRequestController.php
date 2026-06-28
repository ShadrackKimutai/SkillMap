<?php

namespace App\Http\Controllers\User;

use App\Models\JobRequest;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class JobRequestController extends Controller
{
    public function create(): View
    {
        $trades = Trade::all();
        return view('user.job-requests.create', compact('trades'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'trade_id' => ['required', 'exists:trades,id'],
            'budget' => ['required', 'numeric', 'min:0'],
            'date_needed' => ['required', 'date'],
            'location' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        JobRequest::create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        return redirect()->route('user.job-requests.index')->with('success', 'Job request created');
    }

    public function index(): View
    {
        $jobRequests = auth()->user()->jobRequests()->latest()->paginate(15);
        return view('user.job-requests.index', compact('jobRequests'));
    }

    public function show(JobRequest $jobRequest): View
    {
        $this->authorize('view', $jobRequest);
        $quotes = $jobRequest->quotes()->latest()->get();
        return view('user.job-requests.show', compact('jobRequest', 'quotes'));
    }
}
