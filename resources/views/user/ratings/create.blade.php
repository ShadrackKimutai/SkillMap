@extends('layouts.app')

@section('title', 'Rate Tasker')

@section('breadcrumbs')
    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('user.job-requests.index') }}" class="hover:text-blue-600 transition">My Jobs</a>
    <span class="text-gray-300">›</span>
    <a href="{{ route('user.job-requests.show', $jobRequest) }}" class="hover:text-blue-600 transition">{{ Str::limit($jobRequest->title, 30) }}</a>
    <span class="text-gray-300">›</span>
    <span class="text-gray-700 font-medium">Rate Tasker</span>
@endsection

@section('content')
<div class="max-w-lg mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Rate Tasker</h1>
        <p class="text-gray-500 text-sm mt-1">How did {{ $quote?->tasker->name }} do on "{{ $jobRequest->title }}"?</p>
    </div>

    <form method="POST" action="{{ route('user.ratings.store', $jobRequest) }}"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Rating</label>
            <div class="flex items-center gap-2" id="star-group">
                @for($i = 1; $i <= 5; $i++)
                <button type="button" data-value="{{ $i }}"
                        class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition focus:outline-none">★</button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}" required>
            <p class="text-xs text-gray-400 mt-2">Click a star to set your rating</p>
        </div>

        <div>
            <label for="review" class="block text-sm font-medium text-gray-700 mb-1.5">Review</label>
            <textarea name="review" id="review" rows="5"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                      placeholder="Share your experience with this tasker (minimum 10 characters)..." required>{{ old('review') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition text-sm">
                Submit Rating
            </button>
            <a href="{{ route('user.job-requests.show', $jobRequest) }}"
               class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-600 text-sm hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
(function () {
    const stars  = document.querySelectorAll('.star-btn');
    const input  = document.getElementById('rating-input');
    let current  = parseInt(input.value) || 0;

    function paint(n) {
        stars.forEach((s, i) => {
            s.classList.toggle('text-yellow-400', i < n);
            s.classList.toggle('text-gray-300',   i >= n);
        });
    }

    paint(current);

    stars.forEach(btn => {
        btn.addEventListener('mouseenter', () => paint(parseInt(btn.dataset.value)));
        btn.addEventListener('mouseleave', () => paint(current));
        btn.addEventListener('click', () => {
            current = parseInt(btn.dataset.value);
            input.value = current;
            paint(current);
        });
    });
}());
</script>
@endsection
