<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('event', $event) }}
    <x-heading :title="$event->name">
        <div class="mt-1 flex flex-col md:flex-row md:flex-wrap md:space-x-4">
            <!-- location -->
            @if(! empty($event->display_location))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <x-phosphor-map-pin-line-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                {{ $event->display_location }}
            </div>
            @endif
            <!-- duration -->
            @if(! empty($event->from_date) && ! empty($event->to_date))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <x-phosphor-calendar-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                {{ $event->from_to_date }}
            </div>
            @endif
            <!-- link -->
            @if(! empty($event->link))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <x-phosphor-browser-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                <a href="{{ $event->link_url }}" class="">
                    {{ website_host($event->link_url) }}
                </a>

            </div>
            @endif
            <!-- link -->
            @if(! empty($event->playlist_url))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <x-phosphor-playlist-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                <a href="{{ $event->playlist_url }}" class="">
                    {{ ($event->playlist_id) }}
                </a>

            </div>
            @endif
        </div>

    </x-heading>


    <x-event.details :event="$event" />
</x-layouts.page>
