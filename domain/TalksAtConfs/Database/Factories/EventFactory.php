<?php

namespace Domain\TalksAtConfs\Database\Factories;

use App\Models\Model;
use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'conference_id' => Conference::factory(),
            'name' => $this->faker->name,
            'location' => $this->faker->city . ', ' . $this->faker->country,
            'from_date' => '2021-08-28',
            'to_date' => '2021-09-10',
        ];
    }
}
