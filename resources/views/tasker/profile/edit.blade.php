@extends('layouts.app')

@section('title', 'Edit Profile')

@section('breadcrumbs')
    <a href="{{ route('tasker.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('tasker.profile.show') }}" class="hover:text-blue-600 transition">My Profile</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    #map { height: 400px; }
</style>

<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit Profile</h1>

    <form method="POST" action="{{ route('tasker.profile.update') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="trade_id" class="block text-sm font-medium text-gray-700 mb-2">Trade</label>
            <select name="trade_id" id="trade_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Select a trade</option>
                @foreach ($trades as $trade)
                    <option value="{{ $trade->id }}" {{ $profile?->trade_id == $trade->id ? 'selected' : '' }}>
                        {{ $trade->name }}
                    </option>
                @endforeach
            </select>
            @error('trade_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
            <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tell clients about yourself">{{ $profile?->bio }}</textarea>
            @error('bio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate ($)</label>
                <input type="number" name="hourly_rate" id="hourly_rate" step="0.01" min="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $profile?->hourly_rate }}">
                @error('hourly_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="fixed_rate" class="block text-sm font-medium text-gray-700 mb-2">Fixed Rate ($)</label>
                <input type="number" name="fixed_rate" id="fixed_rate" step="0.01" min="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $profile?->fixed_rate }}">
                @error('fixed_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="price_negotiable" value="1" {{ $profile?->price_negotiable ? 'checked' : '' }} class="rounded">
                <span class="ml-2 text-sm font-medium text-gray-700">Price is negotiable</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Specializations</label>
            <div class="space-y-2">
                @forelse ($specializations as $spec)
                    <label class="flex items-center">
                        <input type="checkbox" name="specializations[]" value="{{ $spec->id }}" {{ $profile?->user->specializations->contains($spec->id) ? 'checked' : '' }} class="rounded">
                        <span class="ml-2 text-sm text-gray-700">{{ $spec->name }}</span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">No specializations available for this trade</p>
                @endforelse
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Languages</label>
            <div class="space-y-2">
                @foreach ($languages as $lang)
                    <label class="flex items-center">
                        <input type="checkbox" name="languages[]" value="{{ $lang->id }}" {{ $profile?->user->languages->contains($lang->id) ? 'checked' : '' }} class="rounded">
                        <span class="ml-2 text-sm text-gray-700">{{ $lang->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mb-6 pb-6 border-b">
            <h3 class="text-lg font-semibold mb-4">Shop/Service Location</h3>
            <p class="text-sm text-gray-600 mb-4">Click on the map or enter coordinates to set your shop/service location</p>

            <div id="map" class="mb-4 rounded-lg border border-gray-300"></div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                    <input type="number" name="latitude" id="latitude" step="0.000001" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ auth()->user()->latitude ?? -1.286389 }}" placeholder="-1.286389">
                    @error('latitude')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                    <input type="number" name="longitude" id="longitude" step="0.000001" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ auth()->user()->longitude ?? 36.816667 }}" placeholder="36.816667">
                    @error('longitude')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="button" id="geolocate-btn" class="mt-4 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Use My Current Location
            </button>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Save Changes
            </button>
            <a href="{{ route('tasker.profile.show') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    let map;
    let marker;

    function initMap() {
        const lat = parseFloat(document.getElementById('latitude').value) || -1.286389;
        const lng = parseFloat(document.getElementById('longitude').value) || 36.816667;

        map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        if (lat && lng) {
            marker = L.marker([lat, lng]).addTo(map);
        }

        map.on('click', function(e) {
            const latlng = e.latlng;
            document.getElementById('latitude').value = latlng.lat.toFixed(6);
            document.getElementById('longitude').value = latlng.lng.toFixed(6);

            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng).addTo(map);
            }
        });
    }

    document.getElementById('latitude').addEventListener('change', updateMarkerFromInputs);
    document.getElementById('longitude').addEventListener('change', updateMarkerFromInputs);

    function updateMarkerFromInputs() {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);

        if (lat && lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }
            map.setView([lat, lng], 13);
        }
    }

    document.getElementById('geolocate-btn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
                updateMarkerFromInputs();
            });
        } else {
            alert('Geolocation is not supported by your browser');
        }
    });

    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection
