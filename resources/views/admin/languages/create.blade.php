@extends('layouts.app')

@section('title', 'Add Language')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('admin.languages.index') }}" class="hover:text-blue-600 transition">Languages</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Add Language</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Add Language</h1>

    <form method="POST" action="{{ route('admin.languages.store') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-2">Language Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" placeholder="e.g., English" value="{{ old('name') }}" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Language Code</label>
            <input type="text" name="code" class="w-full border px-3 py-2 rounded" placeholder="e.g., en" maxlength="5" value="{{ old('code') }}" required>
            @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Create Language</button>
            <a href="{{ route('admin.languages.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
