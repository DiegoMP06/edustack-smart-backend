<?php

namespace Database\Seeders\Classroom;

use App\Models\Classroom\CourseStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseStatusSeeder extends Seeder
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
                'color' => '#64748b', // Slate 500 (Neutral)
                'description' => 'El contenido está en preparación. Solo es visible para el instructor y administradores.',
                'order' => 1,
            ],
            [
                'name' => 'Próximamente',
                'slug' => 'upcoming',
                'color' => '#0284c7', // Sky 600 (Información)
                'description' => 'El curso ha sido anunciado, pero las inscripciones aún no están abiertas.',
                'order' => 2,
            ],
            [
                'name' => 'Inscripciones Abiertas',
                'slug' => 'enrollment-open',
                'color' => '#2563eb', // Blue 600 (Acción primaria)
                'description' => 'El curso está activo y aceptando nuevos estudiantes.',
                'order' => 3,
            ],
            [
                'name' => 'En Progreso',
                'slug' => 'in-progress',
                'color' => '#16a34a', // Green 600 (En curso/Éxito)
                'description' => 'Las clases han comenzado. El acceso está restringido a estudiantes inscritos.',
                'order' => 4,
            ],
            [
                'name' => 'Finalizado',
                'slug' => 'finished',
                'color' => '#0f172a', // Slate 900 (Concluido)
                'description' => 'El periodo lectivo ha terminado. El contenido queda disponible como consulta.',
                'order' => 5,
            ],
            [
                'name' => 'Cancelado',
                'slug' => 'cancelled',
                'color' => '#dc2626', // Red 600 (Error/Peligro)
                'description' => 'El curso fue suspendido y no se impartirá.',
                'order' => 6,
            ],
            [
                'name' => 'Archivado',
                'slug' => 'archived',
                'color' => '#475569', // Slate 600 (Histórico)
                'description' => 'Curso fuera del catálogo principal, conservado para fines históricos o de auditoría.',
                'order' => 7,
            ],
        ];

        foreach ($statuses as $status) {
            CourseStatus::create($status);
        }
    }
}
