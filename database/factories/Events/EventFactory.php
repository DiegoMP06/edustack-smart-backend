<?php

namespace Database\Factories\Events;

use App\Models\Events\Event;
use App\Models\Events\EventStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = (clone $startDate)->modify('+3 days');
        $regStart = (clone $startDate)->modify('-1 month');
        $regEnd = (clone $startDate)->modify('-1 week');

        return [
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'summary' => $this->faker->paragraph(),
            'content' => [], // Rich text content usually
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'percent_off' => $this->faker->randomFloat(2, 0, 50),
            'capacity' => $this->faker->numberBetween(10, 500),
            'is_online' => $this->faker->boolean(),
            'online_link' => $this->faker->url(),
            'location' => $this->faker->address(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'registration_started_at' => $regStart,
            'registration_ended_at' => $regEnd,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_published' => $this->faker->boolean(80),
            'event_status_id' => EventStatus::inRandomOrder()->first()?->id ?? EventStatus::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
