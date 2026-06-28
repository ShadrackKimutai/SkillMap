<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Trade;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index(): View
    {
        $trades = Trade::all();
        $languages = Language::all();
        return view('user.search', compact('trades', 'languages'));
    }

    public function search(Request $request): View
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'trade_id' => ['nullable', 'exists:trades,id'],
            'language_id' => ['nullable', 'exists:languages,id'],
            'min_rating' => ['nullable', 'numeric', 'between:0,5'],
        ]);

        $query = User::where('role', 'tasker')
            ->where('verification_status', 'verified')
            ->where('suspension_status', 'none');

        if ($request->trade_id) {
            $query->whereHas('taskerProfile', fn($q) => $q->where('trade_id', $request->trade_id));
            $radius = 50;
        } else {
            $radius = 10;
        }

        $taskers = $query->withinRadius(
            $request->latitude,
            $request->longitude,
            $radius
        )->get();

        if ($request->language_id) {
            $taskers = $taskers->filter(fn($t) => $t->languages->contains('id', $request->language_id));
        }

        if ($request->min_rating) {
            $taskers = $taskers->filter(fn($t) => $t->taskerProfile->average_rating >= $request->min_rating);
        }

        return view('user.search', [
            'trades' => Trade::all(),
            'languages' => Language::all(),
            'taskers' => $taskers,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
    }
}
