<div class="mx-2">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <form class="" method="GET">
            <div>
                <label for="query" class="block text-sm font-medium text-gray-700">Search Conferences</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input wire:model.live="query" type="text"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                        placeholder="Search conferences">
                </div>
            </div>
        </form>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- list of conferences:start -->
        <div class="max-w-none mx-auto">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <ul role="list" class=" divide-y divide-gray-200">

                    @if ($conferences->count() > 0)
                        @foreach ($conferences as $conference)
                            <x-conference.card :conference="$conference" />
                        @endforeach
                    @else
                        <li>
                            <div class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    {{ config('talksatconfs.conferences.messages.no_records') }}
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="mt-5">
            @if ($conferences->count() > 0)
                {{ $conferences->links() }}
            @endif
        </div>
    </div>
</div>
