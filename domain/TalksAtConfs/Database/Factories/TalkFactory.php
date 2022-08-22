<?php

namespace Domain\TalksAtConfs\Database\Factories;

use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;

class TalkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Talk::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'event_id' => Event::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence,
            'talk_date' => $this->faker->date,
        ];
    }
}
