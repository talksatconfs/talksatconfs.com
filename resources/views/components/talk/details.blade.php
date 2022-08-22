<section class="max-w-7xl mx-auto text-gray-700 bg-white">
    <div class="py-5 mx-auto max-w-7xl">

        <section class="w-full px-5 py-6 mx-auto space-y-5 sm:py-8 md:py-12 sm:space-y-8 md:space-y-16 max-w-7xl">
            @php
                $video = $talk->videos->first();

            @endphp
            <div class="flex flex-col bg-white border-2 border-gray-200 rounded-lg overflow-hidden">
                @if(!empty($video))
                    <div
                        class="block top-0 w-full bg-center bg-cover h-64 aspect-w-16 aspect-h-9 relative embed-responsive overflow-hidden">
                        <x-tac.talk.video :video="$talk->videos->first()" :start-time="$talk->video_start_time" />
                    </div>
                @else
                    <div class="m-4 p-2">
                        No video available for this talk. If you think you have the link or any detail, you can  <a class="underline" href="https://github.com/codeat3/talksatconfs/issues/new">create an issue here.</a>
                    </div>
                @endif


                {{-- <div class="flex flex-col flex-1 p-4 sm:p-6">

                    <p class="text-gray-500 mb-8">
                        {{ $talk->description }}
                    </p>
                </div> --}}
            </div>

        </section>
    </div>
</section>
