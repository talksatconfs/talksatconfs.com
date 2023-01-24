<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('conference', $conference) }}
    <x-heading :title="$conference->name">
        <!-- meta of the conf -->
        {{-- flex-col space-x-6 --}}
        {{-- <div class="mt-1 flex flex-col space-x-0 "> --}}
            <div class="mt-1 flex flex-col md:flex-row md:flex-wrap md:space-x-4">
                <!-- website -->
                @if(! empty($conference->website))
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <x-phosphor-browser-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                    <a href="{{ websiteUrl($conference->website) }}">
                        {{ websiteHost($conference->website) }}
                    </a>
                </div>
                @endif
                <!-- twitter -->
                @if(! empty($conference->twitter))
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <x-phosphor-twitter-logo-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                    <a href="{{ $conference->twitter_url }}">
                        {{ $conference->twitter }}
                    </a>
                </div>
                @endif
                <!-- channel -->
                @if(! empty($conference->channel))
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <x-phosphor-youtube-logo-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                    <a href="{{ $conference->channel_url }}">
                        {{ $conference->channel_id }}
                    </a>
                </div>
                @endif
            </div>
    </x-heading>

    <x-conference.details :conference="$conference" />
</x-layouts.page>
