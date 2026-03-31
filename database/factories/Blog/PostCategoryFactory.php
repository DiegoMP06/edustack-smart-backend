<?php

namespace Database\Factories\Blog;

use App\Models\Blog\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCategoryFactory extends Factory
{
    protected $model = PostCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'color' => $this->faker->hexColor(),
            'order' => $this->faker->randomDigit(),
        ];
    }
}
