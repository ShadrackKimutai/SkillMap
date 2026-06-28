@extends('layouts.app')

@section('title', 'Submit Quote')

@section('breadcrumbs')
    <a href="{{ route('tasker.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('tasker.quotes.index') }}" class="hover:text-blue-600 transition">My Quotes</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Submit Quote</span>
@endsection

@section('content')
<?php
$tasker = auth()->user();
$distance = 0;

if ($tasker->latitude && $jobRequest->latitude) {
    $earthRadiusKm = 6371;
    $latFrom = deg2rad($tasker->latitude);
    $lonFrom = deg2rad($tasker->longitude);
    $latTo = deg2rad($jobRequest->latitude);
    $lonTo = deg2rad($jobRequest->longitude);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos($latFrom) * cos($latTo) *
         sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = round($earthRadiusKm * $c, 2);
}
?>

<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Submit a Quote</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('tasker.quotes.store', $jobRequest) }}" class="bg-white p-6 rounded-lg shadow">
                @csrf

                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-lg font-bold mb-4">Job Details</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600 uppercase">Job Title</p>
                            <p class="text-xl font-semibold">{{ $jobRequest->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 uppercase">Trade</p>
                            <p class="font-semibold">{{ $jobRequest->trade->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 uppercase">Client Budget</p>
                            <p class="font-semibold text-green-600">${{ number_format($jobRequest->budget, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 uppercase">Date Needed</p>
                            <p class="font-semibold">{{ $jobRequest->date_needed->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6 pb-6 border-b">
                    <p class="text-sm text-gray-600 uppercase mb-2">Description</p>
                    <p class="text-gray-700 leading-relaxed">{{ $jobRequest->description }}</p>
                </div>

                @if ($jobRequest->location)
                    <div class="mb-6 pb-6 border-b">
                        <p class="text-sm text-gray-600 uppercase mb-2">Job Location</p>
                        <p class="text-gray-700 mb-2">{{ $jobRequest->location }}</p>
                        @if ($distance > 0)
                            <div class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                📍 {{ $distance }} km away
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mb-6">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Your Quote Price ($) *</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your quote price" required>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">Estimated Hours</label>
                    <input type="number" name="estimated_hours" id="estimated_hours" step="0.5" min="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="How many hours will this take?">
                    @error('estimated_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message to Client</label>
                    <textarea name="message" id="message" rows="5" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tell the client about your approach, timeline, or any questions..."></textarea>
                    @error('message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Submit Quote
                    </button>
                    <a href="{{ route('tasker.dashboard') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div>
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Quote Tips</h3>
                <ul class="space-y-3 text-sm text-gray-700">
                    <li>
                        <strong>✓ Be Competitive:</strong> Price your work fairly while considering the client's budget
                    </li>
                    <li>
                        <strong>✓ Be Clear:</strong> Specify exactly what you'll deliver and how long it will take
                    </li>
                    <li>
                        <strong>✓ Be Professional:</strong> Write a clear message explaining your approach
                    </li>
                    <li>
                        <strong>✓ Be Prompt:</strong> Submit quotes quickly to increase your chances
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
