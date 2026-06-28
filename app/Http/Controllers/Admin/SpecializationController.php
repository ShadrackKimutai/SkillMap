<?php

namespace App\Http\Controllers\Admin;

use App\Models\Specialization;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class SpecializationController extends Controller
{
    public function index(Trade $trade = null): View
    {
        $query = Specialization::with('trade');
        if ($trade) {
            $query->where('trade_id', $trade->id);
        }
        $specializations = $query->paginate(15);
        $trades = Trade::all();
        return view('admin.specializations.index', compact('specializations', 'trades', 'trade'));
    }

    public function create(): View
    {
        $trades = Trade::all();
        return view('admin.specializations.create', compact('trades'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trade_id' => ['required', 'exists:trades,id'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        Specialization::create($validated);
        return redirect()->route('admin.specializations.index')->with('success', 'Specialization created');
    }

    public function edit(Specialization $specialization): View
    {
        $trades = Trade::all();
        return view('admin.specializations.edit', compact('specialization', 'trades'));
    }

    public function update(Request $request, Specialization $specialization): RedirectResponse
    {
        $validated = $request->validate([
            'trade_id' => ['required', 'exists:trades,id'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $specialization->update($validated);
        return redirect()->route('admin.specializations.index')->with('success', 'Specialization updated');
    }

    public function destroy(Specialization $specialization): RedirectResponse
    {
        $specialization->delete();
        return redirect()->route('admin.specializations.index')->with('success', 'Specialization deleted');
    }
}
