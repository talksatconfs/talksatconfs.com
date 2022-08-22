<div class="max-w-7xl mx-auto px-6 py-16 sm:px-6 lg:px-8">
    <div class="max-w-screen-lg md:px-8 mx-auto">
      <!-- text - start -->
      <div class="mb-8 md:mb-12">
        <h2 class="text-gray-800 text-2xl lg:text-3xl font-bold text-center mb-4 md:mb-6">
            Some numbers from the website.
        </h2>

        <p class="max-w-screen-md text-gray-500 md:text-lg text-center mx-auto">
          {{-- This is a section of some simple filler text, also known as placeholder text. It shares some characteristics of a real written text but is random or otherwise generated. --}}
        </p>
      </div>
      <!-- text - end -->

      <div class="grid grid-cols-2 md:grid-cols-4 bg-indigo-600 rounded-lg gap-6 md:gap-8 p-6 md:p-8">
        <!-- stat - start -->
        <div class="flex flex-col items-center">
          <div class="text-white text-xl sm:text-2xl md:text-3xl font-bold">{{ $conference_count }}</div>
          <div class="text-indigo-200 text-sm sm:text-base">Conferences</div>
        </div>
        <!-- stat - end -->

        <!-- stat - start -->
        <div class="flex flex-col items-center">
          <div class="text-white text-xl sm:text-2xl md:text-3xl font-bold">{{ $event_count }}</div>
          <div class="text-indigo-200 text-sm sm:text-base">Events</div>
        </div>

        <!-- stat - start -->
        <div class="flex flex-col items-center">
          <div class="text-white text-xl sm:text-2xl md:text-3xl font-bold">{{ $speaker_count }}</div>
          <div class="text-indigo-200 text-sm sm:text-base">Speakers</div>
        </div>
        <!-- stat - end -->

        <!-- stat - start -->
        <div class="flex flex-col items-center">
          <div class="text-white text-xl sm:text-2xl md:text-3xl font-bold">{{ $talk_count }}</div>
          <div class="text-indigo-200 text-sm sm:text-base">Talks</div>
        </div>
        <!-- stat - end -->


      </div>
    </div>
  </div>
