<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAll extends Command
{
    protected $signature = 'cat3:all-import';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->call('cat3:linemaro-import');
        $this->call('cat3:mindchow-import');
        $this->call('cat3:tac-import');
    }
}
