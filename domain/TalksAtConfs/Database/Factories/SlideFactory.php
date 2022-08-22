<?php

namespace Domain\TalksAtConfs\Database\Factories;

use Domain\TalksAtConfs\Models\Slide;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'talk_id' => Talk::factory(),
            'link' => $this->faker->url,
        ];
    }
}
