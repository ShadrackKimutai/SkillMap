@extends('layouts.app')

@section('title', 'My Quotes')

@section('breadcrumbs')
    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">My Quotes</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">My Quotes</h1>
    <p class="text-gray-500 text-sm mt-1">Quotes received from taskers on your jobs</p>
</div>

@if($quotes->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="text-5xl mb-4">💬</div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No quotes yet</h3>
        <p class="text-gray-500 mb-6">Post a job and taskers will send you quotes.</p>
        <a href="{{ route('user.job-requests.create') }}"
           class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
            Post a Job
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($quotes as $quote)
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-1 flex-wrap">
                        <p class="font-semibold text-gray-900">{{ $quote->tasker->name }}</p>
                        <span class="text-xs text-gray-400">on</span>
                        <a href="{{ route('user.job-requests.show', $quote->jobRequest) }}"
                           class="text-sm text-blue-600 hover:underline truncate">
                            {{ $quote->jobRequest->title }}
                        </a>
                    </div>
                    @if($quote->message)
                        <p class="text-sm text-gray-500 truncate mb-3">{{ $quote->message }}</p>
                    @endif
                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 items-center">
                        <span class="font-bold text-blue-600 text-base">${{ number_format($quote->price, 2) }}</span>
                        @if($quote->estimated_hours)
                            <span>~{{ $quote->estimated_hours }}h</span>
                        @endif
                        @if($quote->distance_km)
                            <span>{{ $quote->distance_km }} km away</span>
                        @endif
                        <span class="text-xs px-2 py-0.5 rounded-full
                            @if($quote->status === 'accepted') bg-green-100 text-green-700
                            @elseif($quote->status === 'rejected') bg-red-100 text-red-600
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ ucfirst($quote->status) }}
                        </span>
                    </div>
                </div>
                <div class="shrink-0 flex flex-col gap-2 items-end">
                    <a href="{{ route('user.quotes.show', $quote) }}"
                       class="text-sm text-blue-600 hover:text-blue-700 font-medium">View →</a>
                    @if($quote->status === 'pending')
                        <form method="POST" action="{{ route('user.quotes.accept', $quote) }}">
                            @csrf
                            <button class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition">Accept</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $quotes->links() }}
    </div>
@endif
@endsection
