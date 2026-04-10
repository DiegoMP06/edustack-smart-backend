<?php

namespace Database\Factories\Projects;

use App\Models\Projects\Project;
use App\Models\Projects\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $isPublished = $this->faker->boolean(70);

        $stacks = [
            ['Astro', 'Tailwind CSS', 'TypeScript', 'React'],
            ['Laravel', 'Livewire', 'PostgreSQL', 'Alpine.js'],
            ['Vue.js', 'Pinia', 'Dexie.js', 'Astro'],
            ['Python', 'FastAPI', 'PyTorch', 'WebGPU'],
            ['React', 'Node.js', 'AstroDB', 'Zod']
        ];

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(12),

            'content' => [
                'content' => [
                    [
                        'type' => 'Heading',
                        'props' => ['title' => 'Visión General del Proyecto', 'level' => 2]
                    ],
                    [
                        'type' => 'Paragraph',
                        'props' => ['text' => $this->faker->paragraph(3)]
                    ],
                    [
                        'type' => 'StatsGrid',
                        'props' => [
                            'items' => [
                                ['label' => 'Rendimiento', 'value' => '98%'],
                                ['label' => 'Cobertura de Test', 'value' => '85%'],
                                ['label' => 'Carga Inicial', 'value' => '0.4s'],
                            ]
                        ]
                    ],
                    [
                        'type' => 'Timeline',
                        'props' => [
                            'events' => [
                                ['date' => 'Fase 1', 'content' => 'Diseño de arquitectura y base de datos.'],
                                ['date' => 'Fase 2', 'content' => 'Implementación de módulos CORE.'],
                                ['date' => 'Fase 3', 'content' => 'Despliegue y optimización SEO.'],
                            ]
                        ]
                    ],
                    [
                        'type' => 'CodeBlock',
                        'props' => [
                            'code' => "git clone https://github.com/user/" . Str::slug($name),
                            'language' => 'bash'
                        ]
                    ]
                ],
                'root' => ['props' => ['title' => ucfirst($name)]]
            ],

            'repository_url' => 'https://github.com/diego-meneses/' . Str::slug($name),
            'demo_url' => 'https://' . Str::slug($name) . '.vercel.app',
            'tech_stack' => $this->faker->randomElement($stacks),
            'version' => $this->faker->randomElement(['1.0.0', '1.2.4', '2.0.0-beta']),
            'license' => $this->faker->randomElement(['MIT', 'Apache 2.0', 'GPL-3.0']),

            'is_featured' => $this->faker->boolean(15),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? $this->faker->dateTimeBetween('-6 months', 'now') : null,

            'project_status_id' => ProjectStatus::inRandomOrder()->first()?->id ?? ProjectStatus::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn() => [
            'is_published' => true,
            'published_at' => now(),
            'project_status_id' => ProjectStatus::where('slug', 'published')->first()?->id,
        ]);
    }
}
