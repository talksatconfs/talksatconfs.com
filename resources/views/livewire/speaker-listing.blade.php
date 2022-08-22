<div class="mx-2">
    <div>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <form class="" method="GET">
                <div>
                    <label for="query" class="block text-sm font-medium text-gray-700">Search Speakers</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input wire:model.debounce.500ms="query" type="text"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="Search Speakers">
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if ($speakers->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:gap-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($speakers as $speaker)
                    <x-speaker.card :speaker="$speaker" />
                @endforeach
            </div>
            <div class="mt-5">
                {{ $speakers->links() }}
            </div>
        @else
            <div class="mt-6">
                <div class="px-4 py-4 sm:px-6">
                    {{ config('talksatconfs.speakers.messages.no_records') }}
                </div>
            </div>
        @endif
    </div>
</div>
</div>
