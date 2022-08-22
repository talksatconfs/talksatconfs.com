

<section class="max-w-7xl mx-auto text-gray-700 bg-white">
    <div class="py-5 mx-auto max-w-7xl">
        <!-- listing: start -->
        {{-- <x-tac.talk.listing :talks="$talks" type="subtitle" /> --}}
        <livewire:talk-listing :event="$event" />
        <!-- listing: end -->
    </div>
</section>
