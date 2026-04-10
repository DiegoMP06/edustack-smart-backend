<?php

namespace Database\Seeders\Projects;

use App\Models\Projects\ProjectStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'En Desarrollo',
                'slug' => 'in-development',
                'color' => '#2563eb', // Blue 600
                'description' => 'El proyecto se encuentra en fase de construcción y commits activos.',
                'order' => 1,
            ],
            [
                'name' => 'En Revisión',
                'slug' => 'in-review',
                'color' => '#ca8a04', // Yellow 600
                'description' => 'Sometido a evaluación técnica o revisión por pares (Code Review).',
                'order' => 2,
            ],
            [
                'name' => 'Publicado',
                'slug' => 'published',
                'color' => '#16a34a', // Green 600
                'description' => 'Proyecto finalizado, aprobado y visible en el portafolio público.',
                'order' => 3,
            ],
            [
                'name' => 'En Mantenimiento',
                'slug' => 'maintenance',
                'color' => '#0891b2', // Cyan 600
                'description' => 'Proyecto funcional que recibe actualizaciones menores o corrección de bugs.',
                'order' => 4,
            ],
            [
                'name' => 'Archivado',
                'slug' => 'archived',
                'color' => '#475569', // Slate 600
                'description' => 'Legacy. El proyecto ya no es activo pero permanece como referencia histórica.',
                'order' => 5,
            ],
            [
                'name' => 'Rechazado / Ajustes',
                'slug' => 'rejected',
                'color' => '#dc2626', // Red 600
                'description' => 'No cumple con los estándares mínimos. Requiere cambios estructurales.',
                'order' => 6,
            ],
        ];

        foreach ($statuses as $status) {
            ProjectStatus::create($status);
        }
    }
}
