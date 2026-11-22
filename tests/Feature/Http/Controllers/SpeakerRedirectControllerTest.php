<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Domain\TalksAtConfs\Models\Speaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class SpeakerRedirectControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function old_url_redirects_permanently_to_canonical_url()
    {
        $speaker = Speaker::factory()->create(['name' => 'Linus Torvalds']);

        $this->get('/speakers/' . $speaker->uuid . '/' . $speaker->slug)
            ->assertStatus(301)
            ->assertRedirect($speaker->canonical_url);
    }

    #[Test]
    public function old_url_with_unknown_slug_returns_404()
    {
        Speaker::factory()->create(['name' => 'Linus Torvalds']);

        $this->get('/speakers/some-uuid/nobody-here')->assertNotFound();
    }
}
