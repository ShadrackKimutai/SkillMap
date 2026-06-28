<?php

namespace App\Http\Controllers\Tasker;

use App\Models\TaskerProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $profile = auth()->user()->taskerProfile;
        $trades = \App\Models\Trade::all();
        $languages = \App\Models\Language::all();
        $specializations = $profile?->trade?->specializations ?? [];

        return view('tasker.profile.edit', compact('profile', 'trades', 'languages', 'specializations'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trade_id' => ['required', 'exists:trades,id'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'fixed_rate' => ['nullable', 'numeric', 'min:0'],
            'price_negotiable' => ['boolean'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'specializations' => ['array'],
            'languages' => ['array'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $user = auth()->user();
        $user->update([
            'latitude' => $validated['latitude'] ?? $user->latitude,
            'longitude' => $validated['longitude'] ?? $user->longitude,
        ]);

        TaskerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'trade_id' => $validated['trade_id'],
                'hourly_rate' => $validated['hourly_rate'] ?? null,
                'fixed_rate' => $validated['fixed_rate'] ?? null,
                'price_negotiable' => $validated['price_negotiable'] ?? false,
                'bio' => $validated['bio'] ?? null,
            ]
        );

        if (isset($validated['specializations'])) {
            $user->specializations()->sync($validated['specializations']);
        }

        if (isset($validated['languages'])) {
            $user->languages()->sync($validated['languages']);
        }

        return redirect()->route('tasker.profile.show')->with('success', 'Profile updated');
    }

    public function show(): View
    {
        $profile = auth()->user()->taskerProfile;
        return view('tasker.profile.show', compact('profile'));
    }
}
