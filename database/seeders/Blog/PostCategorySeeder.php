<?php

namespace Database\Seeders\Blog;

use App\Models\Blog\PostCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tecnología',
                'slug' => 'technology',
                'description' => 'Exploración de las últimas tendencias y avances en el ecosistema digital global.',
                'color' => '#2563eb', // Blue 600
                'order' => 1,
            ],
            [
                'name' => 'Programación',
                'slug' => 'programming',
                'description' => 'Fundamentos, lógica y lenguajes para la resolución de problemas mediante código.',
                'color' => '#4f46e5', // Indigo 600
                'order' => 2,
            ],
            [
                'name' => 'Desarrollo Web',
                'slug' => 'web-development',
                'description' => 'Construcción de aplicaciones modernas utilizando stacks modernos y frameworks reactivos.',
                'color' => '#0891b2', // Cyan 600
                'order' => 3,
            ],
            [
                'name' => 'Arquitectura de Software',
                'slug' => 'software-architecture',
                'description' => 'Diseño de sistemas escalables, patrones de diseño y estructuras de alto nivel.',
                'color' => '#7c3aed', // Violet 600
                'order' => 4,
            ],
            [
                'name' => 'Buenas Prácticas',
                'slug' => 'best-practices',
                'description' => 'Clean Code, SOLID, Testing y metodologías para un desarrollo profesional.',
                'color' => '#059669', // Emerald 600
                'order' => 5,
            ],
            [
                'name' => 'Inteligencia Artificial',
                'slug' => 'artificial-intelligence',
                'description' => 'Machine Learning, redes neuronales y automatización inteligente de procesos.',
                'color' => '#9333ea', // Purple 600
                'order' => 6,
            ],
            [
                'name' => 'Bases de Datos',
                'slug' => 'databases',
                'description' => 'Modelado, optimización y gestión de datos en sistemas relacionales y NoSQL.',
                'color' => '#d97706', // Amber 600
                'order' => 7,
            ],
            [
                'name' => 'Ciberseguridad',
                'slug' => 'cybersecurity',
                'description' => 'Protección de infraestructuras, cifrado de datos y prevención de vulnerabilidades.',
                'color' => '#dc2626', // Red 600
                'order' => 8,
            ],
            [
                'name' => 'Internet de las Cosas (IoT)',
                'slug' => 'internet-of-things',
                'description' => 'Interconectividad de dispositivos físicos y sensores mediante redes digitales.',
                'color' => '#0d9488', // Teal 600
                'order' => 9,
            ],
            [
                'name' => 'Proyectos Tecnológicos',
                'slug' => 'tech-projects',
                'description' => 'Gestión, planificación y ejecución de soluciones técnicas en entornos reales.',
                'color' => '#ea580c', // Orange 600
                'order' => 10,
            ],
            [
                'name' => 'Ingeniería TI',
                'slug' => 'information-technology-engineering',
                'description' => 'Administración de sistemas complejos e infraestructura crítica de TI.',
                'color' => '#475569', // Slate 600
                'order' => 11,
            ],
        ];

        foreach ($categories as $category) {
            PostCategory::create($category);
        }
    }
}
