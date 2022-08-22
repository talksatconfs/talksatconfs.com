@if(!empty($speaker->youtube))
<a class="text-gray-500" href="{{ $speaker->youtube_link }}">
    <x-phosphor-youtube-logo-fill class="h-6 w-6" />
</a>
@endif
@if(!empty($speaker->twitter))
<a class="text-gray-500" href="{{ $speaker->twitter_link }}">
    <x-phosphor-twitter-logo-fill class="h-6 w-6" />
</a>
@endif
@if(!empty($speaker->github))
<a class="text-gray-500" href="{{ $speaker->github_link }}">
    <x-phosphor-github-logo-fill class="h-6 w-6" />
</a>
@endif
@if(!empty($speaker->website))
<a class="text-gray-500" href="{{ $speaker->website }}">
    <x-phosphor-browser-fill class="h-6 w-6" />
</a>
@endif
