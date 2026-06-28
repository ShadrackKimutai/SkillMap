@extends('layouts.app')

@section('title', 'My Quotes')

@section('breadcrumbs')
    <a href="{{ route('tasker.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">My Quotes</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">My Quotes</h1>
    <p class="text-gray-600">Manage all your quotes for job requests</p>
</div>

@if ($quotes->count() > 0)
    <div class="space-y-4">
        @foreach ($quotes as $quote)
            <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-blue-600">{{ $quote->jobRequest->title }}</h3>
                        <p class="text-sm text-gray-600">Job ID: #{{ $quote->jobRequest->id }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $quote->status == 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $quote->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $quote->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    ">
                        {{ ucfirst($quote->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Trade</p>
                        <p class="font-semibold">{{ $quote->jobRequest->trade->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Your Quote Price</p>
                        <p class="font-semibold text-green-600">${{ number_format($quote->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Estimated Hours</p>
                        <p class="font-semibold">{{ $quote->estimated_hours ? $quote->estimated_hours . ' hrs' : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Distance</p>
                        <p class="font-semibold">{{ $quote->distance_km ? $quote->distance_km . ' km' : 'N/A' }}</p>
                    </div>
                </div>

                @if ($quote->jobRequest->description)
                    <div class="mb-4 pb-4 border-b">
                        <p class="text-sm text-gray-600 mb-1">Job Description</p>
                        <p class="text-gray-700">{{ Str::limit($quote->jobRequest->description, 200) }}</p>
                    </div>
                @endif

                @if ($quote->message)
                    <div class="mb-4 pb-4 border-b">
                        <p class="text-sm text-gray-600 mb-1">Your Message</p>
                        <p class="text-gray-700">{{ $quote->message }}</p>
                    </div>
                @endif

                <div class="flex gap-3">
                    <a href="{{ route('tasker.quotes.show', $quote) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        View Details
                    </a>
                    @if ($quote->status === 'pending')
                        <a href="{{ route('tasker.quotes.edit', $quote) ?? '#' }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700" style="pointer-events: none; opacity: 0.5;">
                            Edit (Coming Soon)
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $quotes->links() }}
    </div>
@else
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <div class="text-gray-400 text-5xl mb-3">📝</div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Quotes Yet</h3>
        <p class="text-gray-600 mb-4">You haven't submitted any quotes yet. Browse job requests and create quotes to get started.</p>
        <a href="{{ route('user.job-requests.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
            Browse Job Requests
        </a>
    </div>
@endif
@endsection
