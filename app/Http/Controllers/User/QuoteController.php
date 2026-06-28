<?php

namespace App\Http\Controllers\User;

use App\Models\Quote;
use App\Models\ChatThread;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{
    public function index(): View
    {
        $quotes = Quote::whereHas('jobRequest', fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(15);

        return view('user.quotes.index', compact('quotes'));
    }

    public function show(Quote $quote): View
    {
        $this->authorize('view', $quote);
        return view('user.quotes.show', compact('quote'));
    }

    public function accept(Quote $quote): RedirectResponse
    {
        $this->authorize('accept', $quote);

        $quote->update(['status' => 'accepted']);
        $quote->jobRequest->update(['status' => 'quote_accepted']);

        ChatThread::create([
            'job_request_id' => $quote->job_request_id,
            'user_id' => $quote->jobRequest->user_id,
            'tasker_id' => $quote->user_id,
        ]);

        return redirect()->route('user.quotes.show', $quote)->with('success', 'Quote accepted. Chat created.');
    }

    public function reject(Quote $quote): RedirectResponse
    {
        $this->authorize('reject', $quote);
        $quote->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Quote rejected');
    }
}
