
<div>
    <x-tac.talk.search />
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

            {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> --}}
                {{-- <div class="max-w-2xl mx-auto lg:max-w-none"> --}}
                    {{-- <div class="mt-6 space-y-12 lg:space-y-10 lg:grid lg:grid-cols-3 lg:gap-x-6"> --}}

                        @if($talks->count() > 0)
                        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 lg:gap-x-6 xl:grid-cols-3">
                            @foreach ($talks as $talk)
                                <x-tac.talk.card :talk="$talk" />
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

                {{-- </div> --}}
            {{-- </div> --}}

    </div>
</div>
