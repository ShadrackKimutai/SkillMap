@extends('layouts.app')

@section('title', 'Verify Taskers')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Verify Taskers</span>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-6">Tasker Verification Queue</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pending Taskers -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Pending Approval ({{ count($pendingTaskers) }})</h2>

        @forelse ($pendingTaskers as $tasker)
            <div class="bg-white p-6 rounded-lg shadow mb-4">
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $tasker->name }}</h3>
                        <p class="text-gray-600">{{ $tasker->email }}</p>
                        <p class="text-gray-600">{{ $tasker->phone }}</p>
                    </div>
                    <div>
                        <p><strong>Trade:</strong> {{ $tasker->taskerProfile?->trade->name ?? 'N/A' }}</p>
                        <p><strong>Specializations:</strong> {{ count($tasker->specializations) }}</p>
                        <p><strong>Location:</strong> {{ $tasker->latitude }}, {{ $tasker->longitude }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <form method="POST" action="{{ route('admin.taskers.approve', $tasker) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.taskers.reject', $tasker) }}">
                            @csrf
                            <input type="hidden" name="reason" value="Profile needs review">
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-blue-100 text-blue-700 p-4 rounded">
                No pending taskers at this time.
            </div>
        @endforelse
    </div>

    <!-- Verified Taskers -->
    <div>
        <h2 class="text-2xl font-bold mb-4">Verified Taskers ({{ count($allTaskers) }})</h2>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Trade</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Rating</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Verified</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allTaskers as $tasker)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $tasker->name }}</td>
                            <td class="px-6 py-4">{{ $tasker->taskerProfile?->trade->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">⭐ {{ number_format($tasker->taskerProfile?->average_rating ?? 0, 1) }}</td>
                            <td class="px-6 py-4">{{ $tasker->email_verified_at ? '✓' : '✗' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No verified taskers</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
