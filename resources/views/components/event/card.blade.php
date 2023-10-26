<tr>
    <td class="px-6 py-6 whitespace-nowrap">
        <div class="flex items-center">
            {{-- <div class="flex-shrink-0 h-10 w-10">
                <img class="h-10 w-10 rounded-full"
                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60"
                    alt="">
            </div> --}}
            <div class="ml-4">
                <div class=" text-lg font-semibold text-gray-900">
                    <x-anchor :href="$event->canonical_url">{{ $event->name }}</x-anchor>
                </div>
                <div class="font-medium text-gray-500">
                    <x-anchor :href="$event->conference->canonical_url">{{ $event->conference->name }}</x-anchor>
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-gray-900">{{ $event->display_location }}</div>
        {{-- <div class="text-sm text-gray-500">London, USA</div> --}}
    </td>
    <td class="text-sm px-6 py-4 whitespace-nowrap">
        From <span class="font-bold">{{ $event->from_date?->format('d M, Y') }}</span>
        to <span class="font-bold">{{ $event->to_date?->format('d M, Y') }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
        {{ $event->talks_count }} talks
    </td>
</tr>
