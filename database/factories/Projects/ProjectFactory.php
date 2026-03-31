<?php

namespace Database\Factories\Projects;

use App\Models\Projects\Project;
use App\Models\Projects\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'summary' => $this->faker->paragraph(),
            'content' => [],
            'repository_url' => $this->faker->url(),
            'demo_url' => $this->faker->url(),
            'tech_stack' => [$this->faker->word(), $this->faker->word()],
            'version' => '1.0.0',
            'license' => 'MIT',
            'is_featured' => $this->faker->boolean(),
            'is_published' => $this->faker->boolean(),
            'published_at' => $this->faker->dateTime(),
            'project_status_id' => ProjectStatus::factory(),
            'user_id' => User::factory(),
        ];
    }
}
