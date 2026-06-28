@extends('layouts.app')

@section('title', 'Verify Phone')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-2">Verify Your Phone</h2>
    <p class="text-gray-600 mb-6">We need to verify your phone number to complete registration.</p>

    @if (!session('message'))
        <form method="POST" action="{{ route('phone-otp.send') }}" class="mb-6">
            @csrf
            <label class="block font-semibold mb-2">Phone Number</label>
            <input type="tel" name="phone" class="w-full border px-3 py-2 rounded mb-4" placeholder="(555) 123-4567" required>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Send OTP</button>
        </form>
    @else
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>

        <form method="POST" action="{{ route('phone-otp.verify') }}">
            @csrf
            <label class="block font-semibold mb-2">Enter 6-digit OTP</label>
            <input type="text" name="otp" class="w-full border px-3 py-2 rounded mb-4" placeholder="000000" maxlength="6" required>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Verify</button>
        </form>
    @endif
</div>
@endsection
