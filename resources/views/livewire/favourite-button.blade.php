<div class="inline-flex flex-col">
    <button type="button" wire:click="toggle" wire:loading.attr="disabled"
        class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        aria-pressed="{{ $favourited ? 'true' : 'false' }}">
        @if($favourited)
            <x-phosphor-heart-fill class="h-5 w-5 text-red-500" />
            <span>Favourited</span>
        @else
            <x-phosphor-heart-bold class="h-5 w-5 text-gray-500" />
            <span>Favourite</span>
        @endif
    </button>
    @if($message)
        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
    @endif
</div>
