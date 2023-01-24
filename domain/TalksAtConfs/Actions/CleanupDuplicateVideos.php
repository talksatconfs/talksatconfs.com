<?php

namespace Domain\TalksAtConfs\Actions;

use Domain\TalksAtConfs\Models\Video;

class CleanupDuplicateVideos
{
    /**
     * Cleans up the duplicate videos
     *
     * @param string $key //
     */
    public function handle(string $key): Video
    {
        // check if there are 2 videos with the same key
        // and they match the similar source
        $videos = Video::where('key', '=', $key)
            ->orderBy('id')
            ->where(
                function ($query) {
                    $query->where('source', '=', 'youtube.com')
                        ->orWhere('source', '=', 'www.youtube.com')
                        ->orWhere('source', '=', 'youtube');
                }
            )
            ->get();

        $winnerVideo = $videos->first();
        $otherVideos = $videos->skip(1);
        $otherVideos->each(
            function ($video) use ($winnerVideo) {
                if ($video->talks()->count() > 0) {
                    $winnerVideo->talks()->attach(
                        $video->talks()->pluck('id')->toArray()
                    );
                    $video->talks()->detach();
                }
                $video->delete();
            }
        );

        $winnerVideo->source = 'youtube';
        $winnerVideo->save();

        return $winnerVideo;

        // check if both has any talks linked to it

        // if any one has the talk and the other none
        // then delete the one with no talks
        // clean the newly updated talk with the proper source if not

        // if both has no talks attached
        // then delete any one of them
        // clean the newly updated talk with the proper source if not

        // if both has the talks
        // then move the talks to any one of them
        // delete the talks from the another one & delete it
        // clean the newly updated talk with the proper source if not
    }
}
