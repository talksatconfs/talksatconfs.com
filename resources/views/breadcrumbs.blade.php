@unless ($breadcrumbs->isEmpty())
<div class="bg-gray-100 border-b-1">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 rounded-md mt-0">
        <ul class="flex items-center py-2 text-sm overflow-y-auto whitespace-nowrap">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url)
                    @if($loop->first)
                        <li class="text-gray-600">
                        <a href="{{ $breadcrumb->url }}" class="text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                        </a>
                        </li>
                    @else
                        <li class="text-gray-600 hover:underline">
                        <a href="{{ $breadcrumb->url }}" class="text-gray-600 hover:underline">
                            {{ $breadcrumb->title }}
                        </a>
                        </li>
                    @endif
                    @if(!$loop->last)
                    <li class="mx-5 text-gray-600 ">
                    <span class="text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    </li>
                    @endif
                @endif
            @endforeach
            </ul>
    </div>
</div>
@endunless
