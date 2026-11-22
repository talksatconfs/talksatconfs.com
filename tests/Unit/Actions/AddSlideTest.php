<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use Domain\TalksAtConfs\Actions\AddSlide;
use Domain\TalksAtConfs\Models\Slide;
use Domain\TalksAtConfs\Models\Talk;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class AddSlideTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_adds_a_slide_to_a_talk_once()
    {
        $talk = Talk::factory()->create();
        $data = ['talk_id' => $talk->id, 'link' => 'https://slides.example.com/deck'];

        (new AddSlide())->handle($data);
        (new AddSlide())->handle($data);

        $this->assertSame(1, Slide::count());
        $this->assertSame(1, $talk->slides()->count());
    }
}
