<?php

namespace Database\Factories\Projects;

use App\Models\Projects\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectStatusFactory extends Factory
{
    protected $model = ProjectStatus::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'color' => $this->faker->hexColor(),
            'description' => $this->faker->sentence(),
            'order' => $this->faker->randomDigit(),
        ];
    }
}
