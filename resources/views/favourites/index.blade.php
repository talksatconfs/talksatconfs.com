<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    <x-heading title="My favourites">
    </x-heading>
    <div class="mx-2">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-10">
            @if($talks->isEmpty() && $events->isEmpty() && $conferences->isEmpty())
                <p class="text-gray-600">
                    You have not favourited anything yet. Use the
                    <span class="font-medium">Favourite</span> button on any talk, event or conference.
                </p>
            @endif

            @if($talks->isNotEmpty())
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Talks</h2>
                    <ul class="divide-y divide-gray-200 bg-white rounded-lg border border-gray-200">
                        @foreach($talks as $talk)
                            <li class="px-4 py-3">
                                <a href="{{ $talk->canonical_url }}" class="font-semibold text-gray-900">{{ $talk->title }}</a>
                                <div class="text-sm text-gray-500">{{ $talk->event->name }}</div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if($events->isNotEmpty())
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Events</h2>
                    <ul class="divide-y divide-gray-200 bg-white rounded-lg border border-gray-200">
                        @foreach($events as $event)
                            <li class="px-4 py-3">
                                <a href="{{ $event->canonical_url }}" class="font-semibold text-gray-900">{{ $event->name }}</a>
                                @if($event->conference)
                                    <div class="text-sm text-gray-500">{{ $event->conference->name }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if($conferences->isNotEmpty())
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Conferences</h2>
                    <ul class="divide-y divide-gray-200 bg-white rounded-lg border border-gray-200">
                        @foreach($conferences as $conference)
                            <li class="px-4 py-3">
                                <a href="{{ $conference->canonical_url }}" class="font-semibold text-gray-900">{{ $conference->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</x-layouts.page>
