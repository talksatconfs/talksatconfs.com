<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('conferences') }}
    <x-heading title="Conferences">
    </x-heading>
    <livewire:conference-listing />
</x-layouts.page>
