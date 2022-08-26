<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait RecursiveRefreshDatabase
{
    use RefreshDatabase;

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate', $this->migrateUsing());

        $this->artisan('migrate', [
            '--database' => 'talksatconfs',
            '--path' => 'domain/TalksAtConfs/Database/Migrations',
        ]);

        $this->app[Kernel::class]->setArtisan(null);
    }
}
