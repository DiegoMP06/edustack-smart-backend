<?php

namespace Database\Factories\Events;

use App\Models\Events\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventStatus>
 */
class EventStatusFactory extends Factory
{
    protected $model = EventStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'color' => $this->faker->hexColor(),
            'description' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
