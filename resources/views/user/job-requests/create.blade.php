@extends('layouts.app')

@section('title', 'Post a Job')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>#job-map { height: 300px; }</style>
@endsection

@section('breadcrumbs')
    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('user.job-requests.index') }}" class="hover:text-blue-600 transition">My Jobs</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Post a Job</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Post a Job</h1>
        <p class="text-gray-500 text-sm mt-1">Describe what you need and receive quotes from skilled taskers.</p>
    </div>

    <form method="POST" action="{{ route('user.job-requests.store') }}" class="space-y-5">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Job Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="e.g. Fix leaking kitchen pipe" required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                          placeholder="Describe the job in detail..." required>{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="trade_id" class="block text-sm font-medium text-gray-700 mb-1.5">Trade / Category</label>
                    <select name="trade_id" id="trade_id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a trade</option>
                        @foreach($trades as $trade)
                            <option value="{{ $trade->id }}" {{ old('trade_id') == $trade->id ? 'selected' : '' }}>
                                {{ $trade->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700 mb-1.5">Budget ($)</label>
                    <input type="number" name="budget" id="budget" value="{{ old('budget') }}" min="0" step="0.01"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0.00" required>
                </div>
            </div>

            <div>
                <label for="date_needed" class="block text-sm font-medium text-gray-700 mb-1.5">Date Needed</label>
                <input type="date" name="date_needed" id="date_needed" value="{{ old('date_needed') }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Job Location</h2>
                <button type="button" id="use-my-location"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Use my location
                </button>
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">Address / Description</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. Westlands, Nairobi" required>
            </div>

            <p class="text-xs text-gray-400">Click on the map to set the exact location, or use the button above.</p>

            <div class="rounded-lg overflow-hidden border border-gray-200">
                <div id="job-map"></div>
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition text-sm">
                Post Job
            </button>
            <a href="{{ route('user.job-requests.index') }}"
               class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-600 text-sm hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
(function () {
    const defaultLat = {{ old('latitude', -1.286389) }};
    const defaultLng = {{ old('longitude', 36.816667) }};

    const map = L.map('job-map').setView([defaultLat, defaultLng], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
    }).addTo(map);

    const pinIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
    });

    let pin = null;
    if ({{ old('latitude') ? 'true' : 'false' }}) {
        pin = L.marker([defaultLat, defaultLng], { icon: pinIcon }).addTo(map);
    }

    function setPin(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        if (pin) map.removeLayer(pin);
        pin = L.marker([lat, lng], { icon: pinIcon }).addTo(map).bindPopup('Job location').openPopup();
        map.setView([lat, lng], 14);
    }

    map.on('click', e => setPin(e.latlng.lat, e.latlng.lng));

    document.getElementById('use-my-location').addEventListener('click', function () {
        if (!navigator.geolocation) { alert('Geolocation not supported'); return; }
        this.textContent = 'Locating…';
        const btn = this;
        navigator.geolocation.getCurrentPosition(
            pos => { setPin(pos.coords.latitude, pos.coords.longitude); btn.textContent = 'Use my location'; },
            ()  => { alert('Could not get location'); btn.textContent = 'Use my location'; }
        );
    });
}());
</script>
@endsection
