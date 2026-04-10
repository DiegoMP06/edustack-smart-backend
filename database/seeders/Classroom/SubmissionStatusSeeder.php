<?php

namespace Database\Seeders\Classroom;

use App\Models\Classroom\SubmissionStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubmissionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Pendiente',
                'slug' => 'pending',
                'description' => 'La tarea aún no ha sido enviada por el estudiante.',
                'color' => '#64748b', // Slate 500
                'order' => 1,
            ],
            [
                'name' => 'Entregada',
                'slug' => 'submitted',
                'description' => 'Tarea enviada y a la espera de ser revisada por el instructor.',
                'color' => '#2563eb', // Blue 600
                'order' => 2,
            ],
            [
                'name' => 'Entrega Tardía',
                'slug' => 'late',
                'description' => 'Tarea enviada después de la fecha límite establecida.',
                'color' => '#ea580c', // Orange 600
                'order' => 3,
            ],
            [
                'name' => 'Requiere Corrección',
                'slug' => 'needs-correction',
                'description' => 'La tarea fue revisada pero necesita ajustes para ser aprobada.',
                'color' => '#8b5cf6', // Violet 600
                'order' => 4,
            ],
            [
                'name' => 'Reentregada',
                'slug' => 'resubmitted',
                'description' => 'El estudiante ha enviado una versión corregida de la tarea.',
                'color' => '#0891b2', // Cyan 600
                'order' => 5,
            ],
            [
                'name' => 'Calificada',
                'slug' => 'graded',
                'description' => 'La tarea ha sido evaluada y la nota está disponible.',
                'color' => '#16a34a', // Green 600
                'order' => 6,
            ],
            [
                'name' => 'No Entregada',
                'slug' => 'missing',
                'description' => 'El plazo de entrega finalizó y no se recibió el trabajo.',
                'color' => '#dc2626', // Red 600
                'order' => 7,
            ],
        ];

        foreach ($statuses as $status) {
            SubmissionStatus::create($status);
        }
    }
}
