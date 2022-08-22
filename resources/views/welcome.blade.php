<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{-- {{ Breadcrumbs::render('home') }} --}}
    <x-homepage.hero />
    <x-homepage.statistics />
</x-layouts.app>
