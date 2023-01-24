<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SitemapGenerator extends Command
{
    protected $entities = [
        'events',
        'conferences',
        'speakers',
        'talks',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tac:generate-sitemap {entity?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a sitemap';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entity = $this->argument('entity');
        if (
            in_array($entity, $this->entities)
            || is_null($entity)
        ) {
            (new \App\Sitemaps\SitemapGenerator())->generate($entity);
        } else {
            $this->error('Invalid entity provided!');
        }
    }
}
