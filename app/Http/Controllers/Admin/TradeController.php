<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
    public function index(): View
    {
        $trades = Trade::paginate(15);
        return view('admin.trades.index', compact('trades'));
    }

    public function create(): View
    {
        return view('admin.trades.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:trades'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
        ]);

        Trade::create($validated);

        return redirect()->route('admin.trades.index')->with('success', 'Trade created');
    }

    public function edit(Trade $trade): View
    {
        return view('admin.trades.edit', compact('trade'));
    }

    public function update(Request $request, Trade $trade): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:trades,name,' . $trade->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
        ]);

        $trade->update($validated);

        return redirect()->route('admin.trades.index')->with('success', 'Trade updated');
    }

    public function destroy(Trade $trade): RedirectResponse
    {
        $trade->delete();
        return redirect()->route('admin.trades.index')->with('success', 'Trade deleted');
    }
}
