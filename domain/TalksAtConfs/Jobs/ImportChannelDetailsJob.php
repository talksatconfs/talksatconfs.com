<?php

namespace Domain\TalksAtConfs\Jobs;

use Domain\TalksAtConfs\Actions\ImportChannelDetailsAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportChannelDetailsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param string $channel_id // channel id
     *
     * @return void
     */
    public function __construct(protected string $channel_id)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new ImportChannelDetailsAction())->handle($this->channel_id);
    }
}
