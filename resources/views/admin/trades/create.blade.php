@extends('layouts.app')

@section('title', 'Add Trade')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('admin.trades.index') }}" class="hover:text-blue-600 transition">Trades</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Add Trade</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Add Trade</h1>

    <form method="POST" action="{{ route('admin.trades.store') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-2">Trade Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" value="{{ old('name') }}" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Description</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Icon (emoji or text)</label>
            <input type="text" name="icon" class="w-full border px-3 py-2 rounded" value="{{ old('icon') }}">
            @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Create Trade</button>
            <a href="{{ route('admin.trades.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
