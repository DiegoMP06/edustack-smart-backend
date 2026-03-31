<?php

namespace Database\Factories\Events;

use App\Models\Events\EventActivityType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventActivityType>
 */
class EventActivityTypeFactory extends Factory
{
    protected $model = EventActivityType::class;

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
            'description' => $this->faker->sentence(),
            'icon' => 'heroicon-o-star',
            'behavior_type' => $this->faker->randomElement(['competition', 'workshop', 'talk', 'default']),
            'order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
