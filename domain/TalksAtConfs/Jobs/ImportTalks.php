<?php

namespace Domain\TalksAtConfs\Jobs;

use Domain\TalksAtConfs\Actions\ImportTalks as ActionsImportTalks;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportTalks implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(protected $event_id, protected $year, protected $filename)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $importTalks = new ActionsImportTalks(
            $this->event_id,
            $this->year,
            $this->filename,
        );
        $importTalks->handle();
    }
}
