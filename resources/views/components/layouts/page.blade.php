<x-buk-html class="font-sans antialiased"
    :title="isset($title) ? $title . ' - ' . config('talksatconfs.name') : ''"
    >
    <x-slot name="head">
        <x-buk-social-meta
            :url="$canonicalurl"
            :title="$title"
            :description="$description"
            card="summary"
        />

        @if(! empty($canonicalurl))
            <link rel="canonical" href="{{ $canonicalurl }}" />
        @endif
        <x-assets.header />
    </x-slot>

    <!-- wrapper container:start -->
    <div class="">
        <!-- global search:start -->
        {{-- <x-global-search /> --}}
        <!-- global search:end -->
        <!-- header:start -->
        <x-header />
        <!-- header:end -->
            {{ $slot }}
        <!-- footer:start -->
        <x-footer />
        <!-- footer:end -->

        <x-assets.footer />
    </div>
    <!-- wrapper container:end -->

</x-buk-html>
