<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('talk', $talk) }}
    <x-heading :title="$talk->title" type="title">
        <div class="mt-1 flex flex-row flex-wrap space-x-4">
            <div class="flex space-x-2">
                @foreach($talk->speakers as $speaker)
                    <a href="{{ $speaker->canonical_url }}" title="{{ $speaker->name }}" class="">
                        @if(! empty($speaker->search_avatar))
                            <x-buk-avatar class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover object-center"
                            search="{{ $speaker->search_avatar }}" />
                        @else
                            <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover object-center" alt="{{ $speaker->name }}" src="{{ Avatar::create($speaker->name )->toBase64() }}">
                        @endif
                        <p class="inline-block text-sm ml-1 mr-3">
                            {{ $speaker->name }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="mt-1 flex flex-row flex-wrap space-x-4">
            <!-- date -->
            @if(! empty($talk->display_talk_date))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <x-phosphor-calendar-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                {{ $talk->display_talk_date }}
            </div>
            @endif
            <!-- event -->
            @if(! empty($talk->event))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <x-phosphor-presentation-chart-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                <a href="{{ $talk->event->canonical_url }}" class="">
                    {{ $talk->event->name }}
                </a>
            </div>
            @endif
            <!-- conference -->
            @if(! empty($talk->conference))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <x-phosphor-users-four-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                <a href="{{ $talk->conference->canonical_url }}" class="">
                    {{ $talk->conference->name }}
                </a>
            </div>
            @endif
        </div>
    </x-heading>
    <x-talk.details :talk="$talk" />
</x-layouts.page>
