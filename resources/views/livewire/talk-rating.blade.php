<div class="flex flex-wrap items-center gap-x-3 gap-y-1">
    <div class="flex items-center" role="group" aria-label="Rate this talk from 1 to 5">
        @foreach(range(1, 5) as $star)
            <button type="button" wire:click="rate({{ $star }})"
                class="p-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded"
                aria-label="Rate {{ $star }} out of 5"
                title="Rate {{ $star }} out of 5">
                @if($myRating !== null && $star <= $myRating)
                    <x-phosphor-star-fill class="h-6 w-6 text-yellow-400" />
                @else
                    <x-phosphor-star-bold class="h-6 w-6 text-gray-400 hover:text-yellow-400" />
                @endif
            </button>
        @endforeach
    </div>
    <div class="text-sm text-gray-600">
        @if($summary['count'] > 0)
            <span class="font-semibold text-gray-800">{{ number_format($summary['average'], 1) }}</span>
            average from {{ $summary['count'] }} {{ \Illuminate\Support\Str::plural('rating', $summary['count']) }}
        @else
            No ratings yet
        @endif
        @auth
            @if($myRating !== null)
                &middot; you rated {{ $myRating }}
                <button type="button" wire:click="clear" class="underline text-gray-500 hover:text-gray-700">clear</button>
            @endif
        @else
            &middot; <a href="{{ route('login') }}" class="underline">Log in</a> to rate
        @endauth
    </div>
    @if($message)
        <span class="text-xs text-red-600">{{ $message }}</span>
    @endif
</div>
