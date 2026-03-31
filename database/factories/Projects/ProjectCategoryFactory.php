<?php

namespace Database\Factories\Projects;

use App\Models\Projects\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectCategoryFactory extends Factory
{
    protected $model = ProjectCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'color' => $this->faker->hexColor(),
            'icon' => $this->faker->word(),
            'order' => $this->faker->randomDigit(),
        ];
    }
}
