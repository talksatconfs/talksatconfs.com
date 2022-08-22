
<div>
    <x-tac.speaker.search />
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- <section class="w-full py-12 bg-white lg:py-24"> --}}
            {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> --}}




                        @if($speakers->count() > 0)
                        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($speakers as $speaker)
                            <x-tac.speaker.card :speaker="$speaker" />
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

            {{-- </div> --}}
        {{-- </section> --}}
    </div>
</div>
