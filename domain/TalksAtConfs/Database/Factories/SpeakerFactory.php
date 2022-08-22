<?php

namespace Domain\TalksAtConfs\Database\Factories;

use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'twitter' => $this->faker->userName,
            'github' => $this->faker->userName,
        ];
    }
}
