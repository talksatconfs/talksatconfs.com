<div>
    <x-tac.conference.search />
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- list of conferences:start -->
        <div class="max-w-none mx-auto">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <ul role="list" class=" divide-y divide-gray-200">

                    @if($conferences->count() > 0)
                        @foreach ($conferences as $conference)
                            <x-tac.conference.card :conference="$conference" />
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
            @if($conferences->count() > 0)
                {{ $conferences->links() }}
            @endif
        </div>
    </div>
</div>
