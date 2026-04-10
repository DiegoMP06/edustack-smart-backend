<?php

namespace Database\Seeders\Events;

use App\Models\Events\DifficultyLevel;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DifficultyLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Principiante',
                'slug' => 'beginner',
                'color' => '#16a34a', // Green 600
                'description' => 'Introducción desde cero. No se requieren conocimientos previos en el área.',
                'order' => 1,
            ],
            [
                'name' => 'Intermedio',
                'slug' => 'intermediate',
                'color' => '#ca8a04', // Yellow 600
                'description' => 'Requiere comprensión básica de los fundamentos y conceptos técnicos del tema.',
                'order' => 2,
            ],
            [
                'name' => 'Avanzado',
                'slug' => 'advanced',
                'color' => '#ea580c', // Orange 600
                'description' => 'Enfocado en profundizar en técnicas complejas y arquitecturas avanzadas.',
                'order' => 3,
            ],
            [
                'name' => 'Experto',
                'slug' => 'expert',
                'color' => '#dc2626', // Red 600
                'description' => 'Especialización de alto nivel. Requiere años de experiencia o dominio total del área.',
                'order' => 4,
            ],
        ];

        foreach ($levels as $level) {
            DifficultyLevel::create($level);
        }
    }
}
