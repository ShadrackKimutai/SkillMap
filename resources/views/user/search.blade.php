@extends('layouts.app')

@section('title', 'Find Taskers')

@section('breadcrumbs')
    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Find Taskers</span>
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<style>
    #discover-map { height: 520px; }
    .leaflet-routing-container { display: none !important; }
</style>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Find Taskers Near You</h1>
    <p class="text-gray-500 text-sm mt-1">Share your location or drop a pin to discover skilled professionals in your area</p>
</div>

<!-- Controls bar -->
<div class="flex flex-wrap items-center gap-4 mb-4">
    <button id="share-location-btn"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Share My Location
    </button>

    <button id="pin-drop-btn"
        class="bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-700 transition flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
        </svg>
        <span>Drop a Pin</span>
    </button>

    <div class="flex items-center gap-2">
        <label for="radius-select" class="text-sm text-gray-600 font-medium">Radius:</label>
        <select id="radius-select"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="5">5 km</option>
            <option value="10">10 km</option>
            <option value="25" selected>25 km</option>
            <option value="50">50 km</option>
        </select>
    </div>

    <span id="map-status" class="text-sm text-gray-400 italic"></span>
</div>

<!-- Map + Filter panel -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Map -->
    <div class="lg:col-span-2 relative rounded-xl overflow-hidden shadow-md border border-gray-200">
        <div id="discover-map"></div>

        <!-- Overlay shown before location is set -->
        <div id="map-overlay"
            class="absolute inset-0 bg-gray-900 bg-opacity-55 flex items-center justify-center" style="z-index:1000">
            <div class="text-center text-white px-6">
                <div class="text-5xl mb-3">📍</div>
                <h3 class="text-xl font-bold mb-1">See Who's Near You</h3>
                <p class="text-gray-200 text-sm mb-4">Use your device location, or navigate the map and drop a pin manually</p>
                <button id="overlay-pin-btn"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-sm px-5 py-2 rounded-lg border border-white border-opacity-40 transition">
                    Drop a Pin Instead
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & List panel -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-5 flex flex-col gap-4">
        <!-- Empty state -->
        <div id="filter-empty" class="flex-1 flex flex-col items-center justify-center text-center text-gray-400 py-8">
            <div class="text-4xl mb-3">🗺️</div>
            <p class="text-sm">Share your location to filter taskers by trade</p>
        </div>

        <!-- Loading state -->
        <div id="filter-loading" class="hidden flex-1 flex flex-col items-center justify-center text-gray-400 py-8">
            <div class="animate-spin text-3xl mb-3">⏳</div>
            <p class="text-sm">Finding taskers nearby…</p>
        </div>

        <!-- Populated state -->
        <div id="filter-content" class="hidden flex flex-col gap-4 h-full">

            <!-- Active route summary (hidden until a route is drawn) -->
            <div id="route-info" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs text-blue-500 font-semibold uppercase tracking-wide mb-0.5">Route to</p>
                        <p class="font-semibold text-blue-900 text-sm truncate" id="route-tasker-name"></p>
                        <p class="text-blue-700 text-xs mt-1">
                            <span id="route-distance" class="font-medium"></span>
                            &nbsp;&middot;&nbsp;
                            <span id="route-time"></span> by road
                        </p>
                    </div>
                    <button id="clear-route-btn"
                        class="shrink-0 text-blue-300 hover:text-red-500 transition text-2xl leading-none font-light"
                        title="Clear route">&times;</button>
                </div>
            </div>

            <p id="tasker-count" class="text-sm font-semibold text-gray-700"></p>

            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Filter by Trade</h4>
                <div id="trade-filters" class="space-y-1 max-h-48 overflow-y-auto pr-1"></div>
            </div>

            <div class="border-t pt-3">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Nearby Taskers</h4>
                <div id="tasker-list" class="space-y-1 max-h-64 overflow-y-auto pr-1"></div>
            </div>
        </div>
    </div>
</div>

<!-- Quote Request Modal -->
<div id="quote-modal"
    class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Request a Quote</h2>
            <button id="qm-close"
                class="text-gray-400 hover:text-gray-600 text-2xl leading-none font-light">&times;</button>
        </div>

        <!-- Login panel (shown to guests) -->
        <div id="qm-login-panel" class="hidden p-6">
            <p class="text-gray-500 text-sm mb-5">Sign in to send your quote request to this tasker</p>
            <div id="qm-login-error"
                class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg"></div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="qm-email" autocomplete="email"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="qm-password" autocomplete="current-password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button id="qm-login-btn"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition text-sm">
                    Sign In &amp; Continue
                </button>
                <p class="text-center text-xs text-gray-400">
                    No account?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register free</a>
                </p>
            </div>
        </div>

        <!-- Quote form panel -->
        <div id="qm-quote-panel" class="hidden p-6">
            <div class="bg-gray-50 rounded-lg px-4 py-3 mb-5 text-sm border border-gray-100">
                <p class="font-semibold text-gray-800" id="qm-tasker-name"></p>
                <p class="text-gray-400 text-xs mt-0.5" id="qm-tasker-trade"></p>
            </div>
            <form id="qm-form" method="POST" action="{{ url('/user/job-requests') }}" class="space-y-4">
                <input type="hidden" name="_token" id="qm-csrf">
                <input type="hidden" name="trade_id" id="qm-trade-id">
                <input type="hidden" name="latitude"  id="qm-lat">
                <input type="hidden" name="longitude" id="qm-lng">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                    <input type="text" name="title" id="qm-title" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. Fix leaking kitchen pipe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="qm-description" rows="3" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        placeholder="Describe the job in detail…"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Budget (KSh)</label>
                        <input type="number" name="budget" id="qm-budget" min="0" step="100" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Needed</label>
                        <input type="date" name="date_needed" id="qm-date" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" id="qm-location" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Fetching address…">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition text-sm">
                    Send Quote Request
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
<script>
(function () {
    const SHADOW_URL  = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png';
    const MARKER_BASE = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-';
    const PALETTE     = ['red','green','orange','yellow','violet','gold','grey','black'];

    const PALETTE_HEX = {
        red: '#e03131', green: '#2f9e44', orange: '#e8590c', yellow: '#f08c00',
        violet: '#7048e8', gold: '#e67700', grey: '#868e96', black: '#343a40',
        blue: '#1971c2',
    };

    // Use saved profile coordinates if available, otherwise null
    const SAVED_LAT    = {{ auth()->user()->latitude  ?? 'null' }};
    const SAVED_LNG    = {{ auth()->user()->longitude ?? 'null' }};
    const IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};

    let map, userMarker;
    let allMarkers     = [];
    let tradeColorMap  = {};
    let taskersById    = {};
    let colorIdx       = 0;
    let currentLat     = null;
    let currentLng     = null;
    let pinDropMode    = false;
    let routingControl = null;

    function makeIcon(color) {
        return L.icon({
            iconUrl:    MARKER_BASE + color + '.png',
            shadowUrl:  SHADOW_URL,
            iconSize:   [25, 41],
            iconAnchor: [12, 41],
            popupAnchor:[1, -34],
            shadowSize: [41, 41],
        });
    }

    function colorFor(tradeId) {
        if (tradeColorMap[tradeId] === undefined) {
            tradeColorMap[tradeId] = PALETTE[colorIdx % PALETTE.length];
            colorIdx++;
        }
        return tradeColorMap[tradeId];
    }

    function initMap() {
        const startLat = SAVED_LAT ?? -1.286389;
        const startLng = SAVED_LNG ?? 36.816667;
        const zoom     = SAVED_LAT ? 13 : 11;

        map = L.map('discover-map').setView([startLat, startLng], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);

        map.on('click', function (e) {
            if (!pinDropMode) return;
            placeUserPin(e.latlng.lat, e.latlng.lng);
            setPinDropMode(false);
        });
    }

    function setPinDropMode(active) {
        pinDropMode = active;
        const btn = document.getElementById('pin-drop-btn');
        const mapContainer = map.getContainer();
        if (active) {
            btn.classList.replace('bg-gray-600', 'bg-amber-500');
            btn.classList.replace('hover:bg-gray-700', 'hover:bg-amber-600');
            btn.querySelector('span') && (btn.querySelector('span').textContent = 'Click map to drop pin…');
            mapContainer.style.cursor = 'crosshair';
            setStatus('Click anywhere on the map to drop your pin');
        } else {
            btn.classList.replace('bg-amber-500', 'bg-gray-600');
            btn.classList.replace('hover:bg-amber-600', 'hover:bg-gray-700');
            btn.querySelector('span') && (btn.querySelector('span').textContent = 'Drop a Pin');
            mapContainer.style.cursor = '';
            setStatus('');
        }
    }

    function placeUserPin(lat, lng) {
        currentLat = lat;
        currentLng = lng;

        document.getElementById('map-overlay').style.display = 'none';

        if (userMarker) map.removeLayer(userMarker);
        userMarker = L.marker([lat, lng], { icon: makeIcon('blue') })
            .addTo(map)
            .bindPopup('<strong>Your Location</strong>')
            .openPopup();

        fetchTaskers(lat, lng, parseInt(document.getElementById('radius-select').value));
    }

    function clearTaskerMarkers() {
        allMarkers.forEach(({ marker }) => map.removeLayer(marker));
        allMarkers  = [];
        taskersById = {};
        clearRoute();
    }

    function renderMarkers(taskers) {
        taskers.forEach(t => {
            if (!t.lat || !t.lng) return;
            taskersById[t.id] = t;
            const color = colorFor(t.trade_id);
            const popup = `
                <div style="min-width:170px;font-family:system-ui,sans-serif;line-height:1.5">
                    <p style="font-weight:600;margin:0 0 2px;font-size:14px">${t.name}</p>
                    <p style="color:#6b7280;margin:0 0 6px;font-size:12px">${t.trade}</p>
                    <p style="margin:0;font-size:12px;color:#374151">
                        📍 ${t.distance_km} km away
                        ${t.rating      ? `&nbsp;&nbsp;⭐ ${t.rating}/5`   : ''}
                        ${t.hourly_rate ? `<br>💰 KSh ${t.hourly_rate}/hr` : ''}
                    </p>
                    ${t.bio ? `<p style="margin:5px 0 0;font-size:11px;color:#9ca3af;font-style:italic">${t.bio}</p>` : ''}
                    <div style="display:flex;gap:6px;margin-top:10px">
                        <button onclick="window.routeTo(${t.id})"
                            style="flex:1;padding:6px 8px;background:#2563eb;color:#fff;border:none;
                                   border-radius:6px;cursor:pointer;font-size:11px;font-weight:500">
                            🗺️ Route
                        </button>
                        <button onclick="window.openQuoteModal(${t.id})"
                            style="flex:1;padding:6px 8px;background:#16a34a;color:#fff;border:none;
                                   border-radius:6px;cursor:pointer;font-size:11px;font-weight:500">
                            💬 Quote
                        </button>
                    </div>
                </div>`;
            const marker = L.marker([t.lat, t.lng], { icon: makeIcon(color) })
                .addTo(map)
                .bindPopup(popup, { maxWidth: 240 });
            allMarkers.push({ marker, tradeId: t.trade_id });
        });
    }

    function renderFilters(taskers) {
        const trades = {};
        taskers.forEach(t => {
            if (!trades[t.trade_id]) trades[t.trade_id] = { id: t.trade_id, name: t.trade, count: 0 };
            trades[t.trade_id].count++;
        });

        const container = document.getElementById('trade-filters');
        container.innerHTML = '';

        Object.values(trades).forEach(trade => {
            const hex = PALETTE_HEX[colorFor(trade.id)];
            const label = document.createElement('label');
            label.className = 'flex items-center gap-2 cursor-pointer hover:bg-gray-50 rounded px-1 py-0.5';
            label.innerHTML = `
                <input type="checkbox" class="trade-filter rounded" data-trade-id="${trade.id}" checked>
                <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:${hex}"></span>
                <span class="text-sm text-gray-700">${trade.name}
                    <span class="text-gray-400 text-xs">(${trade.count})</span>
                </span>`;
            container.appendChild(label);
        });

        container.querySelectorAll('.trade-filter').forEach(cb => {
            cb.addEventListener('change', function () {
                const id = parseInt(this.dataset.tradeId);
                allMarkers.forEach(({ marker, tradeId }) => {
                    if (tradeId === id) {
                        this.checked ? marker.addTo(map) : map.removeLayer(marker);
                    }
                });
            });
        });
    }

    function renderList(taskers) {
        const container = document.getElementById('tasker-list');
        container.innerHTML = '';
        const visible = taskers.slice(0, 12);

        visible.forEach(t => {
            const hex  = PALETTE_HEX[colorFor(t.trade_id)];
            const item = document.createElement('div');
            item.className = 'flex items-center gap-2 px-2 py-1.5 rounded border border-gray-100 text-sm hover:bg-gray-50 transition';
            item.innerHTML = `
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${hex}"></span>
                <div class="min-w-0 flex-1 cursor-pointer" data-focus>
                    <p class="font-medium truncate text-gray-800">${t.name}</p>
                    <p class="text-gray-400 text-xs">${t.trade} · ${t.distance_km} km</p>
                </div>
                <div class="flex gap-1 shrink-0">
                    <button class="route-list-btn text-xs text-blue-600 hover:text-white
                                   hover:bg-blue-600 px-2 py-1 rounded border border-blue-200
                                   hover:border-blue-600 transition font-medium">
                        Route
                    </button>
                    <button class="quote-list-btn text-xs text-green-600 hover:text-white
                                   hover:bg-green-600 px-2 py-1 rounded border border-green-200
                                   hover:border-green-600 transition font-medium">
                        Quote
                    </button>
                </div>`;

            item.querySelector('[data-focus]').addEventListener('click', () => {
                map.setView([t.lat, t.lng], 15);
                const found = allMarkers.find(m =>
                    m.marker.getLatLng().lat === t.lat && m.marker.getLatLng().lng === t.lng
                );
                if (found) found.marker.openPopup();
            });

            item.querySelector('.route-list-btn').addEventListener('click', e => {
                e.stopPropagation();
                routeTo(t);
            });

            item.querySelector('.quote-list-btn').addEventListener('click', e => {
                e.stopPropagation();
                openQuoteModal(t);
            });

            container.appendChild(item);
        });

        if (taskers.length > 12) {
            const more = document.createElement('p');
            more.className = 'text-xs text-gray-400 text-center mt-2';
            more.textContent = `+ ${taskers.length - 12} more on the map`;
            container.appendChild(more);
        }
    }

    function routeTo(tasker) {
        if (currentLat === null || currentLng === null) {
            setStatus('Share your location first to get directions.');
            return;
        }

        clearRoute();

        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(currentLat, currentLng),
                L.latLng(tasker.lat, tasker.lng),
            ],
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'driving',
            }),
            lineOptions: {
                styles: [{ color: '#2563eb', weight: 5, opacity: 0.85 }],
                extendToWaypoints: true,
                missingRouteTolerance: 0,
            },
            show:               false,
            addWaypoints:       false,
            routeWhileDragging: false,
            fitSelectedRoutes:  true,
            createMarker:       () => null,
        }).addTo(map);

        routingControl.on('routesfound', function (e) {
            const s        = e.routes[0].summary;
            const distKm   = (s.totalDistance / 1000).toFixed(1);
            const totalMin = Math.round(s.totalTime / 60);
            const h        = Math.floor(totalMin / 60);
            const m        = totalMin % 60;
            const timeStr  = h > 0 ? `${h}h ${m}m` : `${totalMin} min`;
            showRouteInfo(tasker, distKm, timeStr);
            setStatus(`Route to ${tasker.name}: ${distKm} km · ~${timeStr}`);
        });

        routingControl.on('routingerror', function () {
            setStatus('Could not calculate route. Try again.');
            clearRoute();
        });

        setStatus(`Getting route to ${tasker.name}…`);
    }

    function clearRoute() {
        if (routingControl) {
            try { routingControl.getPlan().setWaypoints([]); } catch (_) {}
            map.removeControl(routingControl);
            routingControl = null;
        }
        const panel = document.getElementById('route-info');
        if (panel) panel.classList.add('hidden');
    }

    function showRouteInfo(tasker, distKm, timeStr) {
        document.getElementById('route-tasker-name').textContent = tasker.name;
        document.getElementById('route-distance').textContent    = `${distKm} km`;
        document.getElementById('route-time').textContent        = timeStr;
        document.getElementById('route-info').classList.remove('hidden');
    }

    window.routeTo = routeTo;

    // ─── Quote modal ──────────────────────────────────────────────────────────

    function getCsrfToken() {
        const m = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
        return m ? decodeURIComponent(m[1]) : '{{ csrf_token() }}';
    }

    async function reverseGeocode(lat, lng) {
        try {
            const r = await fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lng=${lng}&format=json`
            );
            const d = await r.json();
            return d.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        } catch (_) {
            return `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        }
    }

    function openQuoteModal(taskerIdOrObj) {
        const tasker = typeof taskerIdOrObj === 'object' ? taskerIdOrObj : taskersById[taskerIdOrObj];
        if (!tasker) return;

        openQuoteModal._pending = tasker;

        const modal = document.getElementById('quote-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('qm-tasker-name').textContent  = tasker.name;
        document.getElementById('qm-tasker-trade').textContent = tasker.trade;
        document.getElementById('qm-trade-id').value           = tasker.trade_id;
        document.getElementById('qm-lat').value                = currentLat ?? '';
        document.getElementById('qm-lng').value                = currentLng ?? '';
        document.getElementById('qm-csrf').value               = getCsrfToken();
        document.getElementById('qm-location').value           = '';

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('qm-date').value = tomorrow.toISOString().split('T')[0];

        if (IS_LOGGED_IN) {
            showQuotePanel();
        } else {
            showLoginPanel();
        }

        if (currentLat !== null) {
            reverseGeocode(currentLat, currentLng).then(addr => {
                const el = document.getElementById('qm-location');
                if (el && !el.value) el.value = addr;
            });
        }
    }

    function closeQuoteModal() {
        const modal = document.getElementById('quote-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        const errEl = document.getElementById('qm-login-error');
        errEl.classList.add('hidden');
        errEl.textContent = '';
    }

    function showLoginPanel() {
        document.getElementById('qm-login-panel').classList.remove('hidden');
        document.getElementById('qm-quote-panel').classList.add('hidden');
    }

    function showQuotePanel() {
        document.getElementById('qm-login-panel').classList.add('hidden');
        document.getElementById('qm-quote-panel').classList.remove('hidden');
    }

    window.openQuoteModal = openQuoteModal;

    function setStatus(msg) {
        document.getElementById('map-status').textContent = msg;
    }

    function fetchTaskers(lat, lng, radius) {
        document.getElementById('filter-empty').classList.add('hidden');
        document.getElementById('filter-loading').classList.remove('hidden');
        document.getElementById('filter-content').classList.add('hidden');
        setStatus('');

        fetch(`/api/nearby-taskers?lat=${lat}&lng=${lng}&radius=${radius}`)
            .then(r => {
                if (!r.ok) throw new Error('Request failed');
                return r.json();
            })
            .then(data => {
                clearTaskerMarkers();
                tradeColorMap = {};
                colorIdx      = 0;

                renderMarkers(data.taskers);
                renderFilters(data.taskers);
                renderList(data.taskers);

                document.getElementById('filter-loading').classList.add('hidden');
                document.getElementById('filter-content').classList.remove('hidden');
                const n = data.count;
                document.getElementById('tasker-count').textContent =
                    `${n} tasker${n !== 1 ? 's' : ''} within ${radius} km`;
                setStatus(`${n} tasker${n !== 1 ? 's' : ''} found`);
            })
            .catch(() => {
                document.getElementById('filter-loading').classList.add('hidden');
                document.getElementById('filter-empty').classList.remove('hidden');
                setStatus('Could not load taskers. Try again.');
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initMap();

        // Auto-load taskers if the user's profile has saved coordinates
        if (SAVED_LAT !== null && SAVED_LNG !== null) {
            placeUserPin(SAVED_LAT, SAVED_LNG);
        }

        // Share location button
        document.getElementById('share-location-btn').addEventListener('click', function () {
            if (!navigator.geolocation) {
                setStatus('Geolocation not supported — use Drop a Pin instead.');
                return;
            }
            const btn = this;
            btn.disabled    = true;
            btn.textContent = 'Getting location…';
            setStatus('Requesting location…');

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    map.setView([pos.coords.latitude, pos.coords.longitude], 13);
                    placeUserPin(pos.coords.latitude, pos.coords.longitude);

                    btn.disabled  = false;
                    btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Refresh Location`;
                },
                function () {
                    btn.disabled    = false;
                    btn.textContent = 'Share My Location';
                    setStatus('Location access denied — navigate the map and use Drop a Pin.');
                }
            );
        });

        // Drop-a-pin toggle (controls bar)
        document.getElementById('pin-drop-btn').addEventListener('click', function () {
            if (pinDropMode) {
                setPinDropMode(false);
            } else {
                document.getElementById('map-overlay').style.display = 'none';
                setPinDropMode(true);
            }
        });

        // Drop-a-pin button inside overlay
        document.getElementById('overlay-pin-btn').addEventListener('click', function () {
            document.getElementById('map-overlay').style.display = 'none';
            setPinDropMode(true);
        });

        // Clear route button
        document.getElementById('clear-route-btn').addEventListener('click', clearRoute);

        // Quote modal — close
        document.getElementById('qm-close').addEventListener('click', closeQuoteModal);
        document.getElementById('quote-modal').addEventListener('click', function (e) {
            if (e.target === this) closeQuoteModal();
        });

        // Quote modal — AJAX login then show quote form
        document.getElementById('qm-login-btn').addEventListener('click', async function () {
            const email    = document.getElementById('qm-email').value.trim();
            const password = document.getElementById('qm-password').value;
            const errEl    = document.getElementById('qm-login-error');
            errEl.classList.add('hidden');
            errEl.textContent = '';

            if (!email || !password) {
                errEl.textContent = 'Please enter your email and password.';
                errEl.classList.remove('hidden');
                return;
            }

            this.disabled    = true;
            this.textContent = 'Signing in…';

            try {
                const resp = await fetch('/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ _token: getCsrfToken(), email, password }),
                    redirect: 'follow',
                });
                if (!resp.url.includes('/login')) {
                    document.getElementById('qm-csrf').value = getCsrfToken();
                    showQuotePanel();
                } else {
                    errEl.textContent = 'Invalid email or password.';
                    errEl.classList.remove('hidden');
                }
            } catch (_) {
                errEl.textContent = 'Login failed. Please try again.';
                errEl.classList.remove('hidden');
            }

            this.disabled    = false;
            this.textContent = 'Sign In & Continue';
        });

        // Radius change re-fetches with same pin
        document.getElementById('radius-select').addEventListener('change', function () {
            if (currentLat !== null && currentLng !== null) {
                fetchTaskers(currentLat, currentLng, parseInt(this.value));
            }
        });
    });
}());
</script>
@endsection
