<?php

namespace Database\Factories\Blog;

use App\Models\Blog\PostType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostTypeFactory extends Factory
{
    protected $model = PostType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->word(),
            'order' => $this->faker->randomDigit(),
        ];
    }
}
