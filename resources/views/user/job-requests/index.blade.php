@extends('layouts.app')

@section('title', 'My Jobs')

@section('breadcrumbs')
    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">My Jobs</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Jobs</h1>
        <p class="text-gray-500 text-sm mt-1">Job requests you've posted</p>
    </div>
    <a href="{{ route('user.job-requests.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
        + Post New Job
    </a>
</div>

@if($jobRequests->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="text-5xl mb-4">📋</div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No jobs yet</h3>
        <p class="text-gray-500 mb-6">Post your first job request and get quotes from skilled taskers.</p>
        <a href="{{ route('user.job-requests.create') }}"
           class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
            Post a Job
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($jobRequests as $job)
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('user.job-requests.show', $job) }}"
                           class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition truncate">
                            {{ $job->title }}
                        </a>
                        <span class="shrink-0 text-xs px-2 py-0.5 rounded-full font-medium
                            @if($job->status === 'open') bg-green-100 text-green-700
                            @elseif($job->status === 'quote_accepted') bg-blue-100 text-blue-700
                            @elseif($job->status === 'completed') bg-gray-100 text-gray-600
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm truncate mb-3">{{ $job->description }}</p>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                        <span>{{ $job->trade?->name ?? '—' }}</span>
                        <span>&middot;</span>
                        <span>Budget: <strong class="text-gray-700">${{ number_format($job->budget, 2) }}</strong></span>
                        <span>&middot;</span>
                        <span>Needed: <strong class="text-gray-700">{{ $job->date_needed?->format('M d, Y') }}</strong></span>
                        <span>&middot;</span>
                        <span>{{ $job->quotes->count() }} quote{{ $job->quotes->count() !== 1 ? 's' : '' }}</span>
                    </div>
                </div>
                <a href="{{ route('user.job-requests.show', $job) }}"
                   class="shrink-0 text-sm text-blue-600 hover:text-blue-700 font-medium">
                    View →
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $jobRequests->links() }}
    </div>
@endif
@endsection
