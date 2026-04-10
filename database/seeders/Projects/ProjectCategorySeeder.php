<?php

namespace Database\Seeders\Projects;

use App\Models\Projects\ProjectCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Desarrollo Web',
                'slug' => 'web-development',
                'description' => 'Aplicaciones SPA, SSR, PWA y arquitecturas basadas en componentes.',
                'color' => '#2563eb', // Blue 600
                'icon' => 'globe',
                'order' => 1,
            ],
            [
                'name' => 'Inteligencia Artificial',
                'slug' => 'ai-machine-learning',
                'description' => 'Modelos de ML, visión por computadora y automatización con IA.',
                'color' => '#7c3aed', // Violet 600
                'icon' => 'brain-circuit',
                'order' => 2,
            ],
            [
                'name' => 'Sistemas Embebidos e IoT',
                'slug' => 'iot-embedded',
                'description' => 'Integración de hardware y software con microcontroladores y sensores.',
                'color' => '#059669', // Emerald 600
                'icon' => 'cpu',
                'order' => 3,
            ],
            [
                'name' => 'Aplicaciones Móviles',
                'slug' => 'mobile-apps',
                'description' => 'Desarrollo nativo o híbrido para dispositivos iOS y Android.',
                'color' => '#16a34a', // Green 600
                'icon' => 'smartphone',
                'order' => 4,
            ],
            [
                'name' => 'Ciberseguridad',
                'slug' => 'cybersecurity-projects',
                'description' => 'Proyectos de criptografía, auditoría de sistemas y protección de redes.',
                'color' => '#dc2626', // Red 600
                'icon' => 'shield-check',
                'order' => 5,
            ],
            [
                'name' => 'Ciencia de Datos',
                'slug' => 'data-science',
                'description' => 'Análisis exploratorio, visualización avanzada y minería de datos.',
                'color' => '#0891b2', // Cyan 600
                'icon' => 'database-zap',
                'order' => 6,
            ],
            [
                'name' => 'Videojuegos y Multimedia',
                'slug' => 'game-dev',
                'description' => 'Motores gráficos, diseño de interacciones y experiencias inmersivas.',
                'color' => '#9333ea', // Purple 600
                'icon' => 'gamepad-2',
                'order' => 7,
            ],
            [
                'name' => 'Investigación Tecnológica',
                'slug' => 'tech-research',
                'description' => 'Proyectos científicos y de innovación con bases teóricas y experimentación.',
                'color' => '#4f46e5', // Indigo 600
                'icon' => 'microscope',
                'order' => 8,
            ],
            [
                'name' => 'DevOps e Infraestructura',
                'slug' => 'devops-infra',
                'description' => 'Automatización de despliegues, contenedores y gestión de servidores.',
                'color' => '#ea580c', // Orange 600
                'icon' => 'server-cog',
                'order' => 9,
            ],
            [
                'name' => 'Proyectos de Comunidad',
                'slug' => 'community-open-source',
                'description' => 'Software libre y herramientas sociales desarrolladas colaborativamente.',
                'color' => '#64748b', // Slate 600
                'icon' => 'users-round',
                'order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            ProjectCategory::create($category);
        }
    }
}
