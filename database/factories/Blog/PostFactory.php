<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Post;
use App\Models\Blog\PostType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'summary' => $this->faker->paragraph(),
            'content' => [],
            'views_count' => $this->faker->numberBetween(0, 1000),
            'reading_time_minutes' => $this->faker->numberBetween(1, 15),
            'is_featured' => $this->faker->boolean(),
            'is_published' => $this->faker->boolean(),
            'published_at' => $this->faker->dateTime(),
            'post_type_id' => PostType::factory(),
            'user_id' => User::factory(),
        ];
    }
}
