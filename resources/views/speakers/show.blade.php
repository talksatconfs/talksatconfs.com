<x-layouts.page :title="$title" :description="$description" :canonicalurl="$canonicalurl">
    {{ Breadcrumbs::render('speaker', $speaker) }}
    <x-heading :title="$speaker->name" type="title">
    </x-heading>

    <x-speaker.details :speaker="$speaker" />
</x-layouts.app>
