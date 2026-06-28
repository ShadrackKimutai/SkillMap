@extends('layouts.app')

@section('title', 'My Profile')

@section('breadcrumbs')
    <a href="{{ route('tasker.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">My Profile</span>
@endsection

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    #profileMap { height: 400px; }
</style>

<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">My Profile</h1>
        <a href="{{ route('tasker.profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Edit Profile
        </a>
    </div>

    @if ($profile)
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-2">{{ auth()->user()->name }}</h2>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Trade</h3>
                    <p class="text-lg font-semibold mt-1">{{ $profile->trade->name ?? 'Not set' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Status</h3>
                    <p class="text-lg font-semibold mt-1">
                        <span class="px-3 py-1 rounded-full {{ auth()->user()->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ auth()->user()->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </p>
                </div>
            </div>

            @if ($profile->bio)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Bio</h3>
                    <p class="text-gray-700">{{ $profile->bio }}</p>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Hourly Rate</h3>
                    <p class="text-lg font-semibold mt-1">
                        {{ $profile->hourly_rate ? '$' . number_format($profile->hourly_rate, 2) : 'Not set' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Fixed Rate</h3>
                    <p class="text-lg font-semibold mt-1">
                        {{ $profile->fixed_rate ? '$' . number_format($profile->fixed_rate, 2) : 'Not set' }}
                    </p>
                </div>
            </div>

            <div class="mb-6 pb-6 border-b">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Price Negotiable</h3>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded-full {{ $profile->price_negotiable ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $profile->price_negotiable ? 'Yes' : 'No' }}
                    </span>
                </p>
            </div>

            @if (auth()->user()->latitude && auth()->user()->longitude)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Shop/Service Location</h3>
                    <div id="profileMap" class="mb-4 rounded-lg border border-gray-300"></div>
                    <p class="text-sm text-gray-600">
                        <strong>Coordinates:</strong> {{ auth()->user()->latitude }}, {{ auth()->user()->longitude }}
                    </p>
                </div>
            @endif

            @if ($profile->user->specializations->count() > 0)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Specializations</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($profile->user->specializations as $spec)
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">{{ $spec->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($profile->user->languages->count() > 0)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Languages</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($profile->user->languages as $lang)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">{{ $lang->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="flex gap-4">
            <a href="{{ route('tasker.dashboard') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Back to Dashboard
            </a>
        </div>
    @else
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mb-6">
            <p>You haven't completed your profile yet. <a href="{{ route('tasker.profile.edit') }}" class="font-bold underline">Complete your profile now</a></p>
        </div>
    @endif
</div>

@if (auth()->user()->latitude && auth()->user()->longitude)
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ auth()->user()->latitude }};
        const lng = {{ auth()->user()->longitude }};

        const map = L.map('profileMap').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('<div><strong>{{ auth()->user()->name }}</strong><br>Shop/Service Location</div>')
            .openPopup();
    });
</script>
@endif
@endsection
