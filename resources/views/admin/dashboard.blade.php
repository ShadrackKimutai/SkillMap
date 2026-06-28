@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-4">Admin Dashboard</h1>

    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Total Taskers</h3>
            <p class="text-3xl font-bold">{{ $stats['total_taskers'] }}</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Pending Verification</h3>
            <p class="text-3xl font-bold">{{ $stats['pending_taskers'] }}</p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Active Jobs</h3>
            <p class="text-3xl font-bold">{{ $stats['active_jobs'] }}</p>
        </div>
        <div class="bg-red-100 p-6 rounded-lg">
            <h3 class="text-gray-600 text-sm">Pending Reports</h3>
            <p class="text-3xl font-bold">{{ $stats['pending_reports'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Management</h3>
            <ul class="space-y-2">
                <li><a href="{{ route('admin.taskers.index') }}" class="text-blue-600 hover:underline">Verify Taskers</a></li>
                <li><a href="{{ route('admin.trades.index') }}" class="text-blue-600 hover:underline">Manage Trades</a></li>
                <li><a href="{{ route('admin.specializations.index') }}" class="text-blue-600 hover:underline">Manage Specializations</a></li>
                <li><a href="{{ route('admin.languages.index') }}" class="text-blue-600 hover:underline">Manage Languages</a></li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Pending Taskers ({{ count($pendingTaskers) }})</h3>
            <ul class="space-y-2">
                @foreach ($pendingTaskers as $tasker)
                    <li>
                        <a href="{{ route('admin.taskers.show', $tasker) }}" class="text-blue-600 hover:underline">
                            {{ $tasker->name }} ({{ $tasker->taskerProfile?->trade->name ?? 'N/A' }})
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
