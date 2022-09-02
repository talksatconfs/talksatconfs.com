<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{-- {{ Breadcrumbs::render('home') }} --}}
    <x-homepage.hero />
    <x-homepage.latest-talks />
    <x-homepage.recently-added-talks />
    <x-homepage.statistics />
</x-layouts.app>
