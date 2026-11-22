<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use Domain\TalksAtConfs\Jobs\ImportSpeakers;
use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class ImportSpeakersTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_imports_speakers_from_the_yaml_file()
    {
        Storage::fake('tac-data');
        Storage::disk('tac-data')->put(ImportSpeakers::SPEAKERS_FILE_NAME, <<<'YAML'
            - name: Ada Lovelace
              description: First programmer
              website: https://ada.example.com
              github: ada
            - name: Alan Turing
              twitter: aturing
            YAML);

        (new ImportSpeakers())->handle();

        $this->assertSame(2, Speaker::count());
        $ada = Speaker::whereSlug('ada-lovelace')->first();
        $this->assertSame('First programmer', $ada->bio);
        $this->assertSame('https://ada.example.com', $ada->website);
        $this->assertSame('aturing', Speaker::whereSlug('alan-turing')->first()->twitter);
    }

    #[Test]
    public function running_it_twice_does_not_duplicate_speakers()
    {
        Storage::fake('tac-data');
        Storage::disk('tac-data')->put(ImportSpeakers::SPEAKERS_FILE_NAME, "- name: Ada Lovelace\n");

        (new ImportSpeakers())->handle();
        (new ImportSpeakers())->handle();

        $this->assertSame(1, Speaker::count());
    }
}
