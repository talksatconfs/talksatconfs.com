<div class="group relative mb-16 bg-gray-100 rounded-lg border-2 border-gray-200 shadow-lg">
    @php
        $video = $talk->videos->first();
    @endphp
    @if (!empty($video))
        <div
            class="block border-b border-gray-300 rounded-tr-lg rounded-tl-lg w-full bg-center bg-cover h-64 aspect-w-16 aspect-h-5 overflow-hidden">
            <x-talk.video :video="$video" :start-time="$talk->video_start_time" />
        </div>
    @else
        <div
            class="block border-b border-gray-300 rounded-tr-lg rounded-tl-lg w-full bg-center bg-cover h-64 aspect-w-16 aspect-h-5 overflow-hidden">
            <x-phosphor-video-camera-slash-light class="items-center" />
        </div>
    @endif
    <div class="p-4">
        <h3 class="mt-6 text-sm text-gray-500">
            {{ $talk->display_talk_date }}
        </h3>
        <p class="text-base font-semibold text-gray-800 md:line-clamp-2">
            <x-anchor :href="$talk->canonical_url">
                {{ $talk->title }}
            </x-anchor>
        </p>
        <p class="text-sm text-gray-600 mb-2 md:line-clamp-1">at
            <x-anchor :href="$talk->event->canonical_url">
                {{ $talk->event->name }}
            </x-anchor>
        </p>
        <!-- This example requires Tailwind CSS v2.0+ -->
        <!-- author gravatar -->
        <div>
            <div class="flex -space-x-2 overflow-hidden">
                @foreach ($talk->speakers as $speaker)
                    <a href="{{ $speaker->canonical_url }}" title="{{ $speaker->name }}" class="">
                        @if (!empty($speaker->search_avatar))
                            <x-buk-avatar
                                class="inline-block h-10 w-10 rounded-full ring-2 ring-white  object-cover object-center"
                                search="{{ $speaker->search_avatar }}" />
                        @else
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white object-cover object-center"
                                alt="{{ $speaker->name }}" src="{{ Avatar::create($speaker->name)->toBase64() }}">
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
