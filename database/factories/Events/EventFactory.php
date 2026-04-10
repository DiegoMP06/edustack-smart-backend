<?php

namespace Database\Factories\Events;

use App\Models\Events\Event;
use App\Models\Events\EventStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(4);
        $isOnline = $this->faker->boolean(40);

        $startDate = $this->faker->dateTimeBetween('+1 week', '+2 months');
        $endDate = (clone $startDate)->modify('+' . rand(1, 5) . ' days');
        $regStart = (clone $startDate)->modify('-15 days');
        $regEnd = (clone $startDate)->modify('-1 day');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(2),

            'content' => [
                'content' => [
                    [
                        'type' => 'Heading',
                        'props' => ['title' => 'Detalles del Evento', 'level' => 2]
                    ],
                    [
                        'type' => 'Accordion',
                        'props' => [
                            'items' => [
                                ['title' => '¿A quién va dirigido?', 'content' => 'Estudiantes de ingeniería y entusiastas del desarrollo web.'],
                                ['title' => 'Requisitos', 'content' => 'Conocimientos básicos de JavaScript y muchas ganas de aprender.'],
                            ]
                        ]
                    ],
                    [
                        'type' => 'StatsGrid',
                        'props' => [
                            'items' => [
                                ['label' => 'Horas de sesión', 'value' => '20h'],
                                ['label' => 'Proyectos', 'value' => '3'],
                                ['label' => 'Certificado', 'value' => 'Sí'],
                            ]
                        ]
                    ],
                    [
                        'type' => 'CalloutBox',
                        'props' => [
                            'type' => 'warning',
                            'text' => 'Cupo limitado. Asegura tu lugar antes del cierre de inscripciones.',
                        ]
                    ]
                ],
                'root' => ['props' => ['title' => $name]]
            ],

            'price' => $this->faker->randomElement([0, 199.99, 450.00, 999.00]),
            'percent_off' => $this->faker->randomElement([0, 10, 15, 50]),
            'capacity' => $this->faker->numberBetween(20, 100),

            'is_online' => $isOnline,
            'online_link' => $isOnline ? $this->faker->url() : null,
            'location' => !$isOnline ? 'Auditorio UPT, Panotla, Tlaxcala' : null,
            'lat' => !$isOnline ? 19.31460000 : null,
            'lng' => !$isOnline ? -98.26190000 : null,

            'registration_started_at' => $regStart,
            'registration_ended_at' => $regEnd,
            'start_date' => $startDate,
            'end_date' => $endDate,

            'is_published' => $this->faker->boolean(80),
            'event_status_id' => EventStatus::inRandomOrder()->first()?->id ?? EventStatus::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }

    public function free(): static
    {
        return $this->state(fn() => [
            'price' => 0,
            'percent_off' => 0,
        ]);
    }

    public function inPerson(): static
    {
        return $this->state(fn() => [
            'is_online' => false,
            'location' => 'Laboratorio de Cómputo, Edificio C',
        ]);
    }
}
