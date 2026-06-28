<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skillmap')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ── Top navigation ──────────────────────────────────────────────── --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center h-14 gap-6">

                {{-- Logo --}}
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                       class="text-xl font-bold text-blue-600 tracking-tight shrink-0">Skillmap</a>
                @else
                    <a href="/" class="text-xl font-bold text-blue-600 tracking-tight shrink-0">Skillmap</a>
                @endauth

                {{-- Desktop nav links --}}
                @auth
                <nav class="hidden md:flex items-center gap-0.5 flex-1">
                    @php $role = auth()->user()->role; @endphp

                    @if($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.taskers.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.taskers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Verify Taskers
                        </a>
                        <a href="{{ route('admin.trades.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.trades.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Trades
                        </a>
                        <a href="{{ route('admin.specializations.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.specializations.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Specializations
                        </a>
                        <a href="{{ route('admin.languages.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.languages.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Languages
                        </a>

                    @elseif($role === 'tasker')
                        <a href="{{ route('tasker.dashboard') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('tasker.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tasker.profile.show') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('tasker.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            My Profile
                        </a>
                        <a href="{{ route('tasker.quotes.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('tasker.quotes.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            My Quotes
                        </a>

                    @elseif($role === 'user')
                        <a href="{{ route('user.dashboard') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('user.search.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('user.search.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Find Taskers
                        </a>
                        <a href="{{ route('user.job-requests.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('user.job-requests.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            My Jobs
                        </a>
                        <a href="{{ route('user.quotes.index') }}"
                           class="px-3 py-1.5 rounded-md text-sm font-medium transition {{ request()->routeIs('user.quotes.*', 'user.ratings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            My Quotes
                        </a>
                    @endif
                </nav>
                @endauth

                {{-- Right side: user info + logout + mobile button --}}
                <div class="flex items-center gap-3 ml-auto shrink-0">
                    @auth
                        <div class="hidden md:flex items-center gap-2">
                            <span class="text-sm text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full capitalize">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-sm text-gray-500 hover:text-red-600 px-3 py-1.5 rounded-md border border-gray-200 hover:border-red-200 hover:bg-red-50 transition">
                                Logout
                            </button>
                        </form>
                        <button id="mobile-menu-btn"
                            class="md:hidden p-1.5 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition"
                            aria-label="Toggle menu">
                            <svg id="icon-open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-600 hover:text-blue-600 px-3 py-1.5 rounded-md hover:bg-blue-50 transition">
                           Sign In
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm bg-blue-600 text-white px-4 py-1.5 rounded-md hover:bg-blue-700 transition font-medium">
                           Get Started
                        </a>
                    @endguest
                </div>

            </div>
        </div>

        {{-- Mobile dropdown --}}
        @auth
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white pb-2">
            <div class="max-w-7xl mx-auto px-4 pt-3 pb-1 space-y-0.5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-3 mb-2">
                    {{ auth()->user()->name }} &middot; {{ ucfirst(auth()->user()->role) }}
                </p>
                @php $role = auth()->user()->role; @endphp
                @if($role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
                    <a href="{{ route('admin.taskers.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('admin.taskers.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Verify Taskers</a>
                    <a href="{{ route('admin.trades.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('admin.trades.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Trades</a>
                    <a href="{{ route('admin.specializations.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('admin.specializations.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Specializations</a>
                    <a href="{{ route('admin.languages.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('admin.languages.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Languages</a>
                @elseif($role === 'tasker')
                    <a href="{{ route('tasker.dashboard') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('tasker.dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
                    <a href="{{ route('tasker.profile.show') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('tasker.profile.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">My Profile</a>
                    <a href="{{ route('tasker.quotes.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('tasker.quotes.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">My Quotes</a>
                @elseif($role === 'user')
                    <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
                    <a href="{{ route('user.search.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('user.search.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Find Taskers</a>
                    <a href="{{ route('user.job-requests.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('user.job-requests.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">My Jobs</a>
                    <a href="{{ route('user.quotes.index') }}" class="block px-3 py-2 text-sm rounded-md {{ request()->routeIs('user.quotes.*', 'user.ratings.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">My Quotes</a>
                @endif
            </div>
        </div>
        @endauth
    </header>

    {{-- ── Breadcrumbs ──────────────────────────────────────────────────── --}}
    @if(View::hasSection('breadcrumbs'))
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2">
            <nav class="flex items-center gap-1 text-sm text-gray-500 flex-wrap">
                @yield('breadcrumbs')
            </nav>
        </div>
    </div>
    @endif

    {{-- ── Flash messages ───────────────────────────────────────────────── --}}
    @if($errors->any() || session('success') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-5 w-full">
        @if($errors->any())
            <div class="flex gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-3 text-sm">
                <span class="shrink-0 mt-0.5">✕</span>
                <ul class="space-y-0.5 list-none">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="flex gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-3 text-sm">
                <span class="shrink-0 mt-0.5">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="flex gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-3 text-sm">
                <span class="shrink-0 mt-0.5">✕</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>
    @endif

    {{-- ── Page content ─────────────────────────────────────────────────── --}}
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 py-6 w-full">
        @yield('content')
    </main>

    <script>
        const mobileBtn  = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const iconOpen   = document.getElementById('icon-open');
        const iconClose  = document.getElementById('icon-close');
        if (mobileBtn) {
            mobileBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
