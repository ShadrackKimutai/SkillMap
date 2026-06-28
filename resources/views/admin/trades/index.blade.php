@extends('layouts.app')

@section('title', 'Manage Trades')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Trades</span>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manage Trades</h1>
        <a href="{{ route('admin.trades.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Trade</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Description</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Icon</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trades as $trade)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $trade->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $trade->description }}</td>
                        <td class="px-6 py-4">{{ $trade->icon }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.trades.edit', $trade) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.trades.destroy', $trade) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this trade?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No trades found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $trades->links() }}
    </div>
</div>
@endsection
