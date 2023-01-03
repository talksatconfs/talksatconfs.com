<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('events') }}
    <x-heading title="Events">
    </x-heading>
    <livewire:event-listing />
</x-layouts.page>
