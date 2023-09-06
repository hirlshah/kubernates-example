<?php

namespace Database\Factories;

use App\Models\Event;
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
            'name' => $this->faker->name(),
            'slug' => $this->faker->unique()->slug(),
            'meeting_date' => $this->faker->dateTimeBetween('+1 month', '+2 month')->format('Y-m-d'),
            'meeting_time' => '21:44:00',
            'content' => 'This is Event Test',
            'image' => "events/card-{$this->faker->numberBetween(1, 3)}.png",
            'meeting_url' => 'https://test.rankupevents.com',
            'user_id' => 2,
            'survey_id' => 1,
        ];
    }
}
