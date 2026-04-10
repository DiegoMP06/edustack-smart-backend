<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Post;
use App\Models\Blog\PostType;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $name = $this->faker->sentence(6);
        $isPublished = $this->faker->boolean(80); // 80% de probabilidad de estar publicado

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(2),
            'content' => [
                [
                    'type' => 'Heading',
                    'props' => [
                        'title' => $name,
                        'level' => 1,
                    ]
                ],
                [
                    'type' => 'Paragraph',
                    'props' => [
                        'text' => $this->faker->paragraph(3),
                    ]
                ],
                [
                    'type' => 'CodeBlock',
                    'props' => [
                        'code' => "export const metadata = {\n  title: 'SIGE Post'\n};",
                        'language' => 'typescript',
                        'label' => 'Configuración de ejemplo'
                    ]
                ],
                [
                    'type' => 'CalloutBox',
                    'props' => [
                        'type' => 'info',
                        'text' => 'Este es un post generado automáticamente por el sistema de seeders de SIGE.',
                    ]
                ],
            ],
            'views_count' => $this->faker->numberBetween(0, 5000),
            'reading_time_minutes' => $this->faker->numberBetween(2, 15),
            'is_featured' => $this->faker->boolean(10),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
            'post_type_id' => PostType::inRandomOrder()->first()?->id ?? PostType::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
