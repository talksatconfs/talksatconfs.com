<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\ExportEventForConfpad;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportTacForConfpad extends Command
{
    protected $signature = 'cat3:tac-export-confpad {event_id}';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->disk = Storage::disk('confpad-data');
    }

    public function handle(): void
    {
        $exportEvent = new ExportEventForConfpad($this->argument('event_id'));
        $exportEvent->handle();
    }
}
