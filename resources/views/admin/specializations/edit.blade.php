@extends('layouts.app')

@section('title', 'Edit Specialization')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('admin.specializations.index') }}" class="hover:text-blue-600 transition">Specializations</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Edit Specialization</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit Specialization</h1>

    <form method="POST" action="{{ route('admin.specializations.update', $specialization) }}" class="bg-white p-6 rounded-lg shadow">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-2">Trade</label>
            <select name="trade_id" class="w-full border px-3 py-2 rounded" required>
                @foreach ($trades as $trade)
                    <option value="{{ $trade->id }}" {{ old('trade_id', $specialization->trade_id) == $trade->id ? 'selected' : '' }}>{{ $trade->name }}</option>
                @endforeach
            </select>
            @error('trade_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" value="{{ old('name', $specialization->name) }}" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Description</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description', $specialization->description) }}</textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Update</button>
            <a href="{{ route('admin.specializations.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
