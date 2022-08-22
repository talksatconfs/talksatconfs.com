<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('speakers') }}
    <x-heading title="Speakers">
    </x-heading>
    {{-- <x-speaker.listing :speakers="$speakers" /> --}}
    <livewire:speaker-listing />
</x-layouts.app>
