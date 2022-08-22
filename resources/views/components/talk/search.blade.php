<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <form class="" method="GET">
            <div>
                <label for="query" class="block text-sm font-medium text-gray-700">Search talks</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" name="query" id="query" value="{{ request()->get('query') }}"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                        placeholder="Search talks">
                </div>
            </div>
        </form>

    </div>
</div>
