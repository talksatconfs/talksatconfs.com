<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('conferences') }}
    <x-heading title="Conferences">
    </x-heading>
    {{-- <x-conference.listing :conferences="$conferences" /> --}}
    <livewire:conference-listing />
</x-layouts.page>
