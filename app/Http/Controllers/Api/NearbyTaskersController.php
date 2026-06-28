<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NearbyTaskersController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'lat'    => 'required|numeric|between:-90,90',
            'lng'    => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:1|max:100',
        ]);

        $lat    = (float) $validated['lat'];
        $lng    = (float) $validated['lng'];
        $radius = (float) ($validated['radius'] ?? 25);

        $taskers = User::withRole('tasker')
            ->verified()
            ->notSuspended()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->withinRadius($lat, $lng, $radius)
            ->with('taskerProfile.trade')
            ->get()
            ->map(fn ($tasker) => [
                'id'          => $tasker->id,
                'name'        => $tasker->name,
                'lat'         => (float) $tasker->latitude,
                'lng'         => (float) $tasker->longitude,
                'distance_km' => round((float) $tasker->distance, 1),
                'trade_id'    => $tasker->taskerProfile?->trade_id ?? 0,
                'trade'       => $tasker->taskerProfile?->trade?->name ?? 'General',
                'hourly_rate' => $tasker->taskerProfile?->hourly_rate,
                'rating'      => $tasker->taskerProfile?->average_rating,
                'bio'         => $tasker->taskerProfile?->bio
                    ? Str::limit($tasker->taskerProfile->bio, 80)
                    : null,
            ]);

        return response()->json([
            'taskers' => $taskers,
            'count'   => $taskers->count(),
        ]);
    }
}
