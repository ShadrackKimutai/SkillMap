@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-4">Dashboard</h1>

    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Total Jobs</h3>
            <p class="text-3xl font-bold">{{ $stats['total_jobs'] }}</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Active Jobs</h3>
            <p class="text-3xl font-bold">{{ $stats['active_jobs'] }}</p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Saved Taskers</h3>
            <p class="text-3xl font-bold">{{ $stats['favorites'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <ul class="space-y-2">
                <li><a href="{{ route('user.search.index') }}" class="text-blue-600 hover:underline">Search Taskers</a></li>
                <li><a href="{{ route('user.job-requests.create') }}" class="text-blue-600 hover:underline">Post New Job</a></li>
                <li><a href="{{ route('user.quotes.index') }}" class="text-blue-600 hover:underline">View Quotes</a></li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Pending Quotes ({{ count($pendingQuotes) }})</h3>
            <ul class="space-y-2">
                @forelse ($pendingQuotes as $quote)
                    <li>
                        <a href="{{ route('user.quotes.show', $quote) }}" class="text-blue-600 hover:underline">
                            ${{ number_format($quote->price, 2) }} - {{ $quote->tasker->name }}
                        </a>
                    </li>
                @empty
                    <li class="text-gray-500">No pending quotes</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h3 class="text-xl font-bold mb-4">Recent Jobs</h3>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Title</th>
                    <th class="text-left py-2">Budget</th>
                    <th class="text-left py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentJobs as $job)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2"><a href="{{ route('user.job-requests.show', $job) }}" class="text-blue-600 hover:underline">{{ $job->title }}</a></td>
                        <td>${{ number_format($job->budget, 2) }}</td>
                        <td><span class="px-2 py-1 rounded bg-blue-100 text-sm">{{ ucfirst($job->status) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">No jobs yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
