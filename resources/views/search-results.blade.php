<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('tac-search', request()->get('query')) }}
    <!-- <livewire:event-listing /> -->
    <livewire:talk-listing />
    <livewire:speaker-listing />
</x-layouts.page>
