<?php

namespace Database\Factories\Events;

use App\Models\Events\DifficultyLevel;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityType;
use App\Models\Events\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EventActivity>
 */
class EventActivityFactory extends Factory
{
    protected $model = EventActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        $isCompetition = $this->faker->boolean(30);
        $isOnline = $this->faker->boolean(20);

        $event = Event::inRandomOrder()->first() ?? Event::factory()->create();
        $startedAt = $this->faker->dateTimeBetween($event->start_date, $event->end_date);
        $endedAt = (clone $startedAt)->modify('+' . rand(1, 4) . ' hours');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'content' => [
                'content' => [
                    [
                        'type' => 'Heading',
                        'props' => ['title' => 'Cronograma de la Actividad', 'level' => 3]
                    ],
                    [
                        'type' => 'RichTextBlock',
                        'props' => ['content' => $this->faker->paragraphs(2, true)]
                    ],
                    [
                        'type' => 'CalloutBox',
                        'props' => [
                            'type' => 'info',
                            'text' => 'Asegúrate de traer tu laptop con el entorno de desarrollo configurado.'
                        ]
                    ]
                ],
                'root' => ['props' => ['title' => $name]]
            ],
            'requirements' => 'Laptop, VS Code, Node.js v20+, y conocimientos básicos de Git.',

            'is_online' => $isOnline,
            'online_link' => $isOnline ? $this->faker->url() : null,
            'location' => !$isOnline ? 'Laboratorio de Ingeniería, Edificio B' : null,
            'lat' => !$isOnline ? 19.31460000 : null,
            'lng' => !$isOnline ? -98.26190000 : null,

            'is_competition' => $isCompetition,
            'has_teams' => $isCompetition,
            'requires_team' => $isCompetition,
            'min_team_size' => $isCompetition ? 2 : null,
            'max_team_size' => $isCompetition ? 4 : null,

            'capacity' => $this->faker->numberBetween(15, 40),
            'only_students' => $this->faker->boolean(90),
            'price' => $this->faker->randomElement([0, 0, 50, 100]),

            'speakers' => [
                [
                    'id' => (string) Str::uuid(),
                    'name' => $this->faker->firstName(),
                    'father_last_name' => $this->faker->lastName(),
                    'mother_last_name' => $this->faker->lastName(),
                    'email' => $this->faker->unique()->safeEmail(),
                    'job_title' => $this->faker->jobTitle(),
                    'company' => $this->faker->company(),
                    'social' => [
                        'linkedin' => 'https://linkedin.com/in/' . $this->faker->userName(),
                        'github' => 'https://github.com/' . $this->faker->userName(),
                        'x' => 'https://x.com/' . $this->faker->userName(),
                    ],
                    'biography' => $this->faker->realTextBetween(100, 500),
                ],
            ],

            'repository_url' => $this->faker->boolean(50) ? 'https://github.com/diego-meneses/workshop-repo' : null,
            'is_published' => true,

            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'registration_started_at' => $event->registration_started_at,
            'registration_ended_at' => $event->registration_ended_at,

            'difficulty_level_id' => DifficultyLevel::inRandomOrder()->first()?->id ?? DifficultyLevel::factory(),
            'event_status_id' => EventStatus::inRandomOrder()->first()?->id ?? EventStatus::factory(),
            'event_activity_type_id' => EventActivityType::inRandomOrder()->first()?->id ?? EventActivityType::factory(),
            'event_id' => $event->id,
        ];
    }

    public function competition(): static
    {
        return $this->state(fn() => [
            'is_competition' => true,
            'has_teams' => true,
            'requires_team' => true,
            'min_team_size' => 3,
            'max_team_size' => 5,
        ]);
    }
}
