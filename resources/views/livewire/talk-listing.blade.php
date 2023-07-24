<div class="mx-2">
    <div>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <form class="" method="GET">
                <div>
                    <label for="query" class="block text-sm font-medium text-gray-700">Search talks</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input wire:model.debounce.500ms="query" type="text"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="Search talks">
                    </div>
                </div>
            </form>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold flex min-w-0 items-center">

                </h3>
                <div>
                    <fieldset>
                        <div class="mt-2 space-y-6">
                            <div class="relative flex gap-x-3">
                                <div class="flex h-6 items-center">
                                    <input id="talks-with-videos" wire:model="withVideo" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="talks-with-videos" class="font-medium text-gray-900 pl-3">Talks with videos
                                        only</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

    </div>


    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        @if ($talks->count() > 0)
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 lg:gap-x-6 xl:grid-cols-3">
                @foreach ($talks as $talk)
                    <x-talk.card :talk="$talk" />
                @endforeach
            </div>
            <div class="mt-5">
                {{ $talks->links() }}
            </div>
        @else
            <div class="mt-6">
                <div class="px-4 py-4 sm:px-6">
                    {{ config('talksatconfs.talks.messages.no_records') }}
                </div>
            </div>
        @endif

    </div>
</div>
