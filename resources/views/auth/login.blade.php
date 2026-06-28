@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-2">Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2 rounded" value="{{ old('email') }}" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="ml-2">Remember Me</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Login</button>
    </form>

    <p class="mt-4 text-center">
        Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register here</a>
    </p>
</div>
@endsection
