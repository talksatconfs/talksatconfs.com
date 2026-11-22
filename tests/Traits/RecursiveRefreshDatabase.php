<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * RefreshDatabase for an app with two connections: the default one
 * (users, teams, ...) and the `talksatconfs` one, whose migrations live in
 * domain/TalksAtConfs/Database/Migrations.
 */
trait RecursiveRefreshDatabase
{
    use RefreshDatabase;

    protected function migrateDatabases(): void
    {
        $this->artisan('migrate:fresh', $this->migrateFreshUsing());

        $this->artisan('migrate:fresh', [
            '--database' => config('talksatconfs.database.connection'),
            '--path' => 'domain/TalksAtConfs/Database/Migrations',
        ]);
    }

    /**
     * @return array<int, string|null>
     */
    protected function connectionsToTransact(): array
    {
        return array_values(array_unique([
            config('database.default'),
            config('talksatconfs.database.connection'),
        ]));
    }
}
