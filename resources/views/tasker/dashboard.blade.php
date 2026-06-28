@extends('layouts.app')

@section('title', 'Tasker Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-4">Tasker Dashboard</h1>

    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Total Quotes</h3>
            <p class="text-3xl font-bold">{{ $stats['total_quotes'] }}</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Pending Quotes</h3>
            <p class="text-3xl font-bold">{{ $stats['pending_quotes'] }}</p>
        </div>
        <div class="bg-purple-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Average Rating</h3>
            <p class="text-3xl font-bold">{{ number_format($stats['average_rating'], 1) }}/5</p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Total Ratings</h3>
            <p class="text-3xl font-bold">{{ $stats['total_ratings'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <ul class="space-y-2">
                <li><a href="{{ route('tasker.profile.edit') }}" class="text-blue-600 hover:underline">Edit Profile</a></li>
                <li><a href="{{ route('tasker.quotes.index') }}" class="text-blue-600 hover:underline">View All Quotes</a></li>
                <li><a href="{{ route('tasker.dashboard') }}" class="text-blue-600 hover:underline">Refresh Dashboard</a></li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Recent Quotes</h3>
            <ul class="space-y-2">
                @foreach ($recentQuotes as $quote)
                    <li>
                        <a href="{{ route('tasker.quotes.show', $quote) }}" class="text-blue-600 hover:underline">
                            ${{ number_format($quote->price, 2) }} - {{ $quote->jobRequest->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
