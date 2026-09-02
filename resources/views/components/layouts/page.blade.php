<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ (isset($title) ? $title . ' - ' . config('talksatconfs.name') : '') ?: config('app.name', 'Laravel') }}</title>

    <meta name="twitter:card" content="summary" />

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}" />

    @if (! empty($description))
        <meta name="description" content="{{ $description }}">
        <meta property="og:description" content="{{ $description }}">
    @endif

    <meta property="og:url" content="{{ $canonicalurl }}" />
    <meta property="og:locale" content="{{ app()->getLocale() }}" />

    @if(! empty($canonicalurl))
        <link rel="canonical" href="{{ $canonicalurl }}" />
    @endif
    <x-assets.header />
    <livewire:styles />
</head>
<body class="font-sans antialiased">

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
        <livewire:scripts />
    </div>
    <!-- wrapper container:end -->

</body>
</html>
