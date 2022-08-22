<div {{ $attributes->merge(['class' => 'flex flex-row space-x-1 mb-2']) }}>
    @foreach($tags as $tag)
    <a href="#_"
        class="bg-red-500 flex flex-row px-3 py-1 leading-none w-auto rounded-full text-xs font-medium uppercase text-white">
        {{ $tag->name }}
    </a>
    @endforeach
</div>
