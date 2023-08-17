<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold flex min-w-0 items-center">
                Recently Added Talks
            </h3>
            <div>
                <fieldset>
                    <div class="mt-6 space-y-6">
                        <div class="relative flex gap-x-3">
                            <div class="flex h-6 items-center">
                                <input id="talks-with-videos" wire:model.live="recentWithVideo" type="checkbox"
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

        @if($talks->count() > 0)
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 lg:gap-x-6 xl:grid-cols-3">
            @foreach ($talks as $talk)
                <x-talk.card :talk="$talk" />
            @endforeach
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
