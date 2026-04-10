<?php

namespace Database\Seeders\Classroom;

use App\Models\Classroom\CourseCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Programación',
                'slug' => 'programming',
                'color' => '#4f46e5', // Indigo 600
                'icon' => 'code-2',
                'description' => 'Domina los fundamentos, lógica y algoritmos en los lenguajes más demandados.',
                'order' => 1,
            ],
            [
                'name' => 'Desarrollo Web',
                'slug' => 'web-development',
                'color' => '#2563eb', // Blue 600
                'icon' => 'monitor-play',
                'description' => 'Arquitectura frontend, backend y despliegue de aplicaciones full-stack modernas.',
                'order' => 2,
            ],
            [
                'name' => 'Bases de Datos',
                'slug' => 'databases',
                'color' => '#d97706', // Amber 600
                'icon' => 'database',
                'description' => 'Modelado relacional y NoSQL, optimización de queries y administración de datos.',
                'order' => 3,
            ],
            [
                'name' => 'Inteligencia Artificial',
                'slug' => 'artificial-intelligence',
                'color' => '#9333ea', // Purple 600
                'icon' => 'cpu',
                'description' => 'Implementación de modelos de Machine Learning, Deep Learning e IA generativa.',
                'order' => 4,
            ],
            [
                'name' => 'Ciberseguridad',
                'slug' => 'cybersecurity',
                'color' => '#dc2626', // Red 600
                'icon' => 'shield-check',
                'description' => 'Técnicas de seguridad informática, hacking ético y protección de infraestructuras.',
                'order' => 5,
            ],
            [
                'name' => 'Redes e Infraestructura',
                'slug' => 'networking',
                'color' => '#0284c7', // Sky 600
                'icon' => 'server',
                'description' => 'Configuración de redes, protocolos de comunicación y administración de servidores.',
                'order' => 6,
            ],
            [
                'name' => 'DevOps y Cloud',
                'slug' => 'devops-cloud',
                'color' => '#ea580c', // Orange 600
                'icon' => 'cloud-cog',
                'description' => 'Cultura CI/CD, contenedorización con Docker y orquestación en la nube.',
                'order' => 7,
            ],
            [
                'name' => 'Desarrollo Móvil',
                'slug' => 'mobile-development',
                'color' => '#16a34a', // Green 600
                'icon' => 'smartphone',
                'description' => 'Creación de aplicaciones nativas e híbridas para ecosistemas iOS y Android.',
                'order' => 8,
            ],
            [
                'name' => 'Gestión de Proyectos TI',
                'slug' => 'it-project-management',
                'color' => '#475569', // Slate 600
                'icon' => 'kanban',
                'description' => 'Metodologías ágiles, Scrum y marcos de trabajo para equipos de ingeniería.',
                'order' => 9,
            ],
            [
                'name' => 'Habilidades Blandas',
                'slug' => 'soft-skills',
                'color' => '#65a30d', // Lime 600
                'icon' => 'users-round',
                'description' => 'Liderazgo, comunicación efectiva y resolución de conflictos en entornos técnicos.',
                'order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
}
