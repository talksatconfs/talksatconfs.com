<?php

namespace Domain\TalksAtConfs\Jobs;

use Domain\TalksAtConfs\Actions\ImportEvent as ActionsImportEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(protected $conference_id, protected $year, protected $filename)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $importEvent = new ActionsImportEvent(
            $this->conference_id,
            $this->year,
            $this->filename,
        );
        $importEvent->handle();
    }
}
