<div class="w-full border border-gray-200 rounded-lg shadow-md p-4">
    <div class="flex items-center">
        <div class="flex-shrink-0 h-16 w-16">
            @if (!empty($speaker->search_avatar))
                <x-buk-avatar class="w-16 h-16 mb-6  object-cover object-center rounded-full"
                    search="{{ $speaker->search_avatar }}" />
            @else
                <img alt="{{ $speaker->name }}" class="w-16 h-16 mb-6 rounded-full"
                    src="{{ Avatar::create($speaker->name)->toBase64() }}">
            @endif
        </div>
        <div class="ml-4">
            <div class="text-sm font-medium text-gray-900">
                <a href="{{ $speaker->canonical_url }}" class="">
                    {{ $speaker->name }}
                </a>
            </div>
            <div class="text-sm text-gray-500">{{ $speaker->talks_count }} talks</div>
            <div class="mt-2 flex flex-row space-x-2">
                @if (!empty($speaker->website))
                    <div class="text-sm text-gray-500">
                        <a href="{{ $speaker->website }}">
                            <x-phosphor-browser-fill class="w-4 h-4 fill-current" />
                        </a>
                    </div>
                @endif
                @if (!empty($speaker->twitter))
                    <div class="text-sm text-gray-500">
                        <a href="{{ $speaker->twitter_link }}">
                            <x-phosphor-twitter-logo-fill class="w-4 h-4 fill-current" />
                        </a>
                    </div>
                @endif
                @if (!empty($speaker->github))
                    <div class="text-sm text-gray-500">
                        <a href="{{ $speaker->github_link }}">
                            <x-phosphor-github-logo-fill class="w-4 h-4 fill-current" />
                        </a>
                    </div>
                @endif
                @if (!empty($speaker->youtube))
                    <div class="text-sm text-gray-500">
                        <a href="{{ $speaker->youtube_link }}">
                            <x-phosphor-youtube-logo-fill class="w-4 h-4 fill-current" />
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
