@extends('layouts.app')

@section('title', $jobRequest->title)

@section('breadcrumbs')
    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('user.job-requests.index') }}" class="hover:text-blue-600 transition">My Jobs</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">{{ Str::limit($jobRequest->title, 40) }}</span>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Job details --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <h1 class="text-2xl font-bold text-gray-900">{{ $jobRequest->title }}</h1>
                <span class="shrink-0 text-sm px-3 py-1 rounded-full font-medium
                    @if($jobRequest->status === 'open') bg-green-100 text-green-700
                    @elseif($jobRequest->status === 'quote_accepted') bg-blue-100 text-blue-700
                    @elseif($jobRequest->status === 'completed') bg-gray-100 text-gray-600
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ ucfirst(str_replace('_', ' ', $jobRequest->status)) }}
                </span>
            </div>

            <p class="text-gray-600 mb-6 leading-relaxed">{{ $jobRequest->description }}</p>

            <dl class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <dt class="text-gray-400 font-medium mb-1">Trade</dt>
                    <dd class="text-gray-800">{{ $jobRequest->trade?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 font-medium mb-1">Budget</dt>
                    <dd class="text-gray-800 font-semibold">${{ number_format($jobRequest->budget, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 font-medium mb-1">Date Needed</dt>
                    <dd class="text-gray-800">{{ $jobRequest->date_needed?->format('M d, Y') }}</dd>
                </div>
                <div class="col-span-2 sm:col-span-3">
                    <dt class="text-gray-400 font-medium mb-1">Location</dt>
                    <dd class="text-gray-800">{{ $jobRequest->location }}</dd>
                </div>
            </dl>
        </div>

        {{-- Quotes on this job --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Quotes <span class="text-gray-400 font-normal">({{ $quotes->count() }})</span>
            </h2>

            @if($quotes->isEmpty())
                <p class="text-gray-400 text-sm py-4 text-center">No quotes yet. Taskers will quote once they see your job.</p>
            @else
                <div class="space-y-4">
                    @foreach($quotes as $quote)
                    <div class="border border-gray-100 rounded-lg p-4 hover:border-gray-200 transition">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $quote->tasker->name }}</p>
                                <p class="text-xs text-gray-400">{{ $quote->tasker->taskerProfile?->trade?->name }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-lg font-bold text-blue-600">${{ number_format($quote->price, 2) }}</p>
                                @if($quote->estimated_hours)
                                    <p class="text-xs text-gray-400">~{{ $quote->estimated_hours }}h</p>
                                @endif
                            </div>
                        </div>
                        @if($quote->message)
                            <p class="text-sm text-gray-600 mb-3">{{ $quote->message }}</p>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($quote->status === 'accepted') bg-green-100 text-green-700
                                @elseif($quote->status === 'rejected') bg-red-100 text-red-600
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($quote->status) }}
                            </span>
                            @if($quote->status === 'pending')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('user.quotes.accept', $quote) }}">
                                        @csrf
                                        <button class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition">Accept</button>
                                    </form>
                                    <form method="POST" action="{{ route('user.quotes.reject', $quote) }}">
                                        @csrf
                                        <button class="text-xs border border-red-200 text-red-600 px-3 py-1.5 rounded-md hover:bg-red-50 transition">Decline</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar actions --}}
    <div class="space-y-4">
        @if($jobRequest->status === 'quote_accepted' && $jobRequest->acceptedQuote && !$jobRequest->ratings()->exists())
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <h3 class="font-semibold text-blue-900 mb-2">Job Accepted</h3>
            <p class="text-sm text-blue-700 mb-4">Your job is underway. Rate the tasker once it's complete.</p>
            <a href="{{ route('user.ratings.create', $jobRequest) }}"
               class="block text-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Rate Tasker
            </a>
        </div>
        @endif

        @if($jobRequest->status === 'completed')
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <h3 class="font-semibold text-green-900 mb-1">Job Complete</h3>
            <p class="text-sm text-green-700">This job has been completed and rated.</p>
        </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 p-5 text-sm space-y-3 text-gray-600">
            <p><strong class="text-gray-800 block mb-0.5">Posted</strong>{{ $jobRequest->created_at->format('M d, Y') }}</p>
            <p><strong class="text-gray-800 block mb-0.5">Quotes received</strong>{{ $quotes->count() }}</p>
        </div>

        <a href="{{ route('user.job-requests.index') }}"
           class="block text-center text-sm text-gray-600 hover:text-blue-600 border border-gray-200 hover:border-blue-300 px-4 py-2 rounded-lg transition">
            ← Back to My Jobs
        </a>
    </div>
</div>
@endsection
