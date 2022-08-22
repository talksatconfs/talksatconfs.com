<div class="mx-2">
    <div>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <form class="" method="GET">
                <div>
                    <label for="query" class="block text-sm font-medium text-gray-700">Search Events</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input wire:model.debounce.500ms="query" type="text"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="Search events">
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Event
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dates
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Number of talks
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if ($events->count() > 0)
                                    @foreach ($events as $event)
                                        <x-event.card :event="$event" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap">
                                            {{ config('talksatconfs.events.messages.no_records') }}
                                        </td>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if ($events->count() > 0)
                        <div class="mt-5">
                            {{ $events->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
