<?php

namespace Database\Seeders\Events;

use App\Models\Events\EventStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Borrador',
                'slug' => 'draft',
                'color' => '#64748b', // Slate 600
                'description' => 'El evento está en fase de planificación y no es visible para el público.',
                'order' => 1,
            ],
            [
                'name' => 'Próximamente',
                'slug' => 'upcoming',
                'color' => '#2563eb', // Blue 600
                'description' => 'Evento anunciado y visible, pero el registro de asistentes aún no ha iniciado.',
                'order' => 2,
            ],
            [
                'name' => 'Inscripciones Abiertas',
                'slug' => 'open',
                'color' => '#16a34a', // Green 600
                'description' => 'El registro está activo. Los usuarios pueden inscribirse a las actividades.',
                'order' => 3,
            ],
            [
                'name' => 'Inscripciones Cerradas',
                'slug' => 'closed',
                'color' => '#ea580c', // Orange 600
                'description' => 'El cupo se ha completado o el periodo de registro ha finalizado.',
                'order' => 4,
            ],
            [
                'name' => 'En Curso',
                'slug' => 'ongoing',
                'color' => '#0891b2', // Cyan 600
                'description' => 'El evento se está desarrollando actualmente.',
                'order' => 5,
            ],
            [
                'name' => 'Finalizado',
                'slug' => 'finished',
                'color' => '#1e293b', // Slate 800
                'description' => 'El evento ha concluido. El material y memorias pueden estar disponibles.',
                'order' => 6,
            ],
            [
                'name' => 'Cancelado',
                'slug' => 'cancelled',
                'color' => '#dc2626', // Red 600
                'description' => 'El evento fue suspendido de forma definitiva.',
                'order' => 7,
            ],
        ];

        foreach ($statuses as $status) {
            EventStatus::create($status);
        }
    }
}
