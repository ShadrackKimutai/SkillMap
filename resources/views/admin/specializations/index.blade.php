@extends('layouts.app')

@section('title', 'Manage Specializations')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Specializations</span>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manage Specializations</h1>
        <a href="{{ route('admin.specializations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Specialization</a>
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
                    <th class="px-6 py-3 text-left text-sm font-semibold">Trade</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Description</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($specializations as $spec)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $spec->trade->name }}</td>
                        <td class="px-6 py-4">{{ $spec->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $spec->description }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.specializations.edit', $spec) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.specializations.destroy', $spec) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No specializations found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $specializations->links() }}
    </div>
</div>
@endsection
