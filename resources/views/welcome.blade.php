<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{-- {{ Breadcrumbs::render('home') }} --}}
    <x-homepage.hero />
    <livewire:latest-talks />
    <livewire:recently-added-talks />
    <x-homepage.statistics />
</x-layouts.page>
