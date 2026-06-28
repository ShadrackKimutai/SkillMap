@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-2">Verify Your Email</h2>
    <p class="text-gray-600 mb-6">We've sent a verification link to your email address.</p>

    <div class="bg-blue-100 text-blue-700 p-4 rounded mb-6">
        <p>Check your email and click the verification link to continue.</p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">
            Resend Verification Email
        </button>
    </form>

    <div class="mt-4 border-t pt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-blue-600 hover:underline">Logout</button>
        </form>
    </div>
</div>
@endsection
