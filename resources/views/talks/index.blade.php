<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('talks') }}
    <x-heading title="Talks">
    </x-heading>
    {{-- <x-talk.listing :talks="$talks" /> --}}
    <livewire:talk-listing />
</x-layouts.app>
