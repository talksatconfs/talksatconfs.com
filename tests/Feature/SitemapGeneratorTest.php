<?php

declare(strict_types=1);

namespace Tests\Feature;

use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class SitemapGeneratorTest extends TestCase
{
    use RecursiveRefreshDatabase;

    private string $sitemapPath = 'sitemaps-test';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'talksatconfs.sitemap_path' => $this->sitemapPath,
            'talksatconfs.domain' => 'https://talksatconfs.example',
        ]);

        File::ensureDirectoryExists(public_path($this->sitemapPath));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(public_path($this->sitemapPath));
        File::delete(public_path('sitemap.xml'));

        parent::tearDown();
    }

    #[Test]
    public function it_writes_an_index_with_absolute_urls_and_entity_sitemaps(): void
    {
        $conference = Conference::factory()->create();
        $event = Event::factory()->create();
        $speaker = Speaker::factory()->create();
        $talk = Talk::factory()->for($event)->create();

        $this->artisan('tac:generate-sitemap')->assertSuccessful();

        $index = File::get(public_path('sitemap.xml'));
        foreach (['conferences', 'events', 'speakers', 'talks'] as $entity) {
            $this->assertStringContainsString(
                "https://talksatconfs.example/{$this->sitemapPath}/{$entity}_sitemap.xml",
                $index
            );
            $this->assertFileExists(public_path("{$this->sitemapPath}/{$entity}_sitemap.xml"));
        }

        $this->assertStringContainsString($conference->canonical_url, $this->read('conferences'));
        $this->assertStringContainsString($event->canonical_url, $this->read('events'));
        $this->assertStringContainsString($speaker->canonical_url, $this->read('speakers'));
        $this->assertStringContainsString($talk->canonical_url, $this->read('talks'));
    }

    #[Test]
    public function it_skips_speakers_without_a_name(): void
    {
        $named = Speaker::factory()->create();
        Speaker::factory()->create(['name' => '', 'slug' => 'nameless']);

        $this->artisan('tac:generate-sitemap')->assertSuccessful();

        $this->assertSame(1, substr_count($this->read('speakers'), '<url>'));
        $this->assertStringContainsString($named->canonical_url, $this->read('speakers'));
    }

    #[Test]
    public function it_can_generate_a_single_entity(): void
    {
        $this->artisan('tac:generate-sitemap', ['entity' => 'speakers'])->assertSuccessful();

        $this->assertFileExists(public_path("{$this->sitemapPath}/speakers_sitemap.xml"));
        $this->assertFileDoesNotExist(public_path("{$this->sitemapPath}/talks_sitemap.xml"));
    }

    #[Test]
    public function it_rejects_an_unknown_entity(): void
    {
        $this->artisan('tac:generate-sitemap', ['entity' => 'bogus'])
            ->expectsOutputToContain('Invalid entity provided!');
    }

    private function read(string $entity): string
    {
        return File::get(public_path("{$this->sitemapPath}/{$entity}_sitemap.xml"));
    }
}
