<div>
    <x-tac.event.search />
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
                                @if($events->count() > 0)
                                    @foreach ($events as $event)
                                    <x-tac.event.card :event="$event" />
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

                    @if($events->count() > 0)
                    <div class="mt-5">
                        {{ $events->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
