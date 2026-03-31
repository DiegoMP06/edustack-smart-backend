<?php

namespace Database\Factories\Events;

use App\Models\Events\DifficultyLevel;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventActivityType;
use App\Models\Events\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $startedAt = $this->faker->dateTimeBetween('now', '+1 month');
        $endedAt = (clone $startedAt)->modify('+2 hours');

        return [
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'summary' => $this->faker->paragraph(),
            'content' => [],
            'requirements' => $this->faker->text(),
            'is_online' => $this->faker->boolean(),
            'online_link' => $this->faker->url(),
            'location' => $this->faker->address(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'has_teams' => $this->faker->boolean(),
            'requires_team' => $this->faker->boolean(),
            'min_team_size' => 1,
            'max_team_size' => 5,
            'max_participants' => $this->faker->numberBetween(10, 50),
            'only_students' => $this->faker->boolean(),
            'is_competition' => $this->faker->boolean(),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'speakers' => [],
            'is_published' => $this->faker->boolean(80),
            'difficulty_level_id' => DifficultyLevel::inRandomOrder()->first()?->id ?? DifficultyLevel::factory(),
            'event_status_id' => EventStatus::inRandomOrder()->first()?->id ?? EventStatus::factory(),
            'event_id' => Event::inRandomOrder()->first()?->id ?? Event::factory(),
            'event_activity_type_id' => EventActivityType::inRandomOrder()->first()?->id ?? EventActivityType::factory(),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ];
    }
}
