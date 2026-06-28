@extends('layouts.app')

@section('title', 'Quote Details')

@section('breadcrumbs')
    <a href="{{ route('tasker.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('tasker.quotes.index') }}" class="hover:text-blue-600 transition">My Quotes</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Quote Details</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Quote Details</h1>
        <span class="px-4 py-2 rounded-full text-sm font-semibold
            {{ $quote->status == 'accepted' ? 'bg-green-100 text-green-800' : '' }}
            {{ $quote->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
            {{ $quote->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
        ">
            {{ ucfirst($quote->status) }}
        </span>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">{{ $quote->jobRequest->title }}</h2>

        <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
            <div>
                <p class="text-sm text-gray-600 uppercase">Client</p>
                <p class="font-semibold">{{ $quote->jobRequest->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 uppercase">Trade</p>
                <p class="font-semibold">{{ $quote->jobRequest->trade->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 uppercase">Your Quote Price</p>
                <p class="text-2xl font-bold text-green-600">${{ number_format($quote->price, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 uppercase">Distance</p>
                <p class="text-2xl font-bold text-blue-600">{{ $quote->distance_km ? $quote->distance_km . ' km' : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 uppercase">Estimated Hours</p>
                <p class="font-semibold">{{ $quote->estimated_hours ? $quote->estimated_hours . ' hours' : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 uppercase">Client Budget</p>
                <p class="font-semibold">${{ number_format($quote->jobRequest->budget, 2) }}</p>
            </div>
        </div>

        <div class="mb-6 pb-6 border-b">
            <p class="text-sm text-gray-600 uppercase mb-2">Job Description</p>
            <p class="text-gray-700 leading-relaxed">{{ $quote->jobRequest->description }}</p>
        </div>

        @if ($quote->job_location)
            <div class="mb-6 pb-6 border-b">
                <p class="text-sm text-gray-600 uppercase mb-2">Job Location</p>
                <p class="text-gray-700 mb-2">{{ $quote->job_location }}</p>
                @if ($quote->distance_km)
                    <div class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        📍 {{ $quote->distance_km }} km from your location
                    </div>
                @endif
            </div>
        @endif

        @if ($quote->jobRequest->location)
            <div class="mb-6 pb-6 border-b">
                <p class="text-sm text-gray-600 uppercase mb-2">Job Location</p>
                <p class="text-gray-700">{{ $quote->jobRequest->location }}</p>
            </div>
        @endif

        @if ($quote->message)
            <div class="mb-6 pb-6 border-b">
                <p class="text-sm text-gray-600 uppercase mb-2">Your Message</p>
                <p class="text-gray-700 leading-relaxed">{{ $quote->message }}</p>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <p class="uppercase text-xs">Quote Submitted</p>
                <p>{{ $quote->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div>
                <p class="uppercase text-xs">Last Updated</p>
                <p>{{ $quote->updated_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Client Information</h3>
        <div class="space-y-3">
            <p><strong>Name:</strong> {{ $quote->jobRequest->user->name }}</p>
            <p><strong>Email:</strong> {{ $quote->jobRequest->user->email }}</p>
            @if ($quote->jobRequest->user->phone)
                <p><strong>Phone:</strong> {{ $quote->jobRequest->user->phone }}</p>
            @endif
        </div>
    </div>

    @if ($quote->status === 'accepted')
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-green-800 mb-2">Quote Accepted ✓</h3>
            <p class="text-green-700 mb-4">The client has accepted your quote. You can now start communication about the job details.</p>
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Start Chat with Client
            </button>
        </div>
    @elseif ($quote->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-red-800 mb-2">Quote Rejected</h3>
            <p class="text-red-700">The client has chosen another quote or declined your offer.</p>
        </div>
    @elseif ($quote->status === 'pending')
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-blue-800 mb-2">Pending Response</h3>
            <p class="text-blue-700">The client is reviewing your quote. You'll be notified when they respond.</p>
        </div>
    @endif

    <div class="flex gap-4">
        <a href="{{ route('tasker.quotes.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
            Back to Quotes
        </a>
        <a href="{{ route('tasker.dashboard') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
