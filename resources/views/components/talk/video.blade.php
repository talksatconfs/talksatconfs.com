@if ($video)
    @php
        $start_time = (! empty($startTime)) ? '?start='.$startTime : null;
        $embed_link = $video->video_embed_link . $start_time;
    @endphp
    <iframe class="w-full aspect-video" title="Title" src="{{ $embed_link }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
@endif
