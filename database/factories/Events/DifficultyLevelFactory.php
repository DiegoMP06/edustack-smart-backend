<?php

namespace Database\Factories\Events;

use App\Models\Events\DifficultyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DifficultyLevel>
 */
class DifficultyLevelFactory extends Factory
{
    protected $model = DifficultyLevel::class;

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
