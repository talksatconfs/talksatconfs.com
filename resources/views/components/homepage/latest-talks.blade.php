<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <h3 class="text-lg font-bold">
            Latest Talks
        </h3>

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
