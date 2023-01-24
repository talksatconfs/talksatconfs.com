<li>
    <div class="block hover:bg-gray-50">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-lg font-bold text-indigo-600 truncate">
                    <a href="{{ $conference->canonical_url }}">
                        {{ $conference->name }}
                    </a>
                </div>
                <div class="ml-2 flex-shrink-0 flex">
                    <!-- channel -->
                    @if(! empty($conference->events_count))
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        {{ $conference->events_count }} events
                        <x-phosphor-youtube-logo-bold class="flex-shrink-0 mr-1.5 ml-2 h-5 w-5 text-gray-700" />
                    </div>
                    @endif
                </div>
            </div>
            <div class="mt-2 flex justify-between">
                <div class="sm:flex">
                    <div class="mr-6 flex flex-col md:flex-row md:items-center text-sm text-gray-500 md:space-x-4">
                        @if(! empty($conference->website))
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <x-phosphor-browser-bold class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-700" />
                            <a href="{{ websiteUrl($conference->website) }}">
                                {{ websiteHost($conference->website, true) }}
                            </a>
                        </div>
                        @endif

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
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    @if(! empty($conference->talks_count))
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        {{ $conference->talks_count }} talks
                        <x-phosphor-microphone-bold class="flex-shrink-0 mr-1.5 ml-2 h-5 w-5 text-gray-700" />
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</li>
