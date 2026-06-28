@extends('layouts.app')

@section('title', 'Quote Details')

@section('breadcrumbs')
    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('user.quotes.index') }}" class="hover:text-blue-600 transition">My Quotes</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Quote Details</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-start justify-between gap-4 mb-5">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Quote from {{ $quote->tasker->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    For: <a href="{{ route('user.job-requests.show', $quote->jobRequest) }}"
                            class="text-blue-600 hover:underline">{{ $quote->jobRequest->title }}</a>
                </p>
            </div>
            <span class="shrink-0 text-sm px-3 py-1 rounded-full font-medium
                @if($quote->status === 'accepted') bg-green-100 text-green-700
                @elseif($quote->status === 'rejected') bg-red-100 text-red-600
                @else bg-yellow-100 text-yellow-700 @endif">
                {{ ucfirst($quote->status) }}
            </span>
        </div>

        <dl class="grid grid-cols-2 gap-4 text-sm mb-5">
            <div>
                <dt class="text-gray-400 font-medium mb-1">Price</dt>
                <dd class="text-2xl font-bold text-blue-600">${{ number_format($quote->price, 2) }}</dd>
            </div>
            @if($quote->estimated_hours)
            <div>
                <dt class="text-gray-400 font-medium mb-1">Estimated Hours</dt>
                <dd class="text-gray-800">{{ $quote->estimated_hours }}h</dd>
            </div>
            @endif
            @if($quote->distance_km)
            <div>
                <dt class="text-gray-400 font-medium mb-1">Distance</dt>
                <dd class="text-gray-800">{{ $quote->distance_km }} km</dd>
            </div>
            @endif
            <div>
                <dt class="text-gray-400 font-medium mb-1">Trade</dt>
                <dd class="text-gray-800">{{ $quote->tasker->taskerProfile?->trade?->name ?? '—' }}</dd>
            </div>
        </dl>

        @if($quote->message)
        <div class="bg-gray-50 rounded-lg p-4 mb-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-2">Tasker's Message</p>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $quote->message }}</p>
        </div>
        @endif

        @if($quote->status === 'pending')
        <div class="flex gap-3 pt-2">
            <form method="POST" action="{{ route('user.quotes.accept', $quote) }}">
                @csrf
                <button class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                    Accept Quote
                </button>
            </form>
            <form method="POST" action="{{ route('user.quotes.reject', $quote) }}">
                @csrf
                <button class="border border-red-200 text-red-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition">
                    Decline
                </button>
            </form>
        </div>
        @endif

        @if($quote->status === 'accepted')
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-2">
            <p class="text-sm text-green-700 font-medium">Quote accepted — a chat thread has been created with this tasker.</p>
        </div>
        @endif
    </div>

    <a href="{{ route('user.quotes.index') }}"
       class="block text-center text-sm text-gray-600 hover:text-blue-600 border border-gray-200 hover:border-blue-300 px-4 py-2 rounded-lg transition">
        ← Back to My Quotes
    </a>
</div>
@endsection
