<?php

namespace Database\Seeders\Blog;

use App\Models\Blog\PostType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Noticia',
                'slug' => 'news',
                'description' => 'Actualidad, novedades y eventos recientes de la industria tecnológica.',
                'icon' => 'newspaper',
                'order' => 1,
            ],
            [
                'name' => 'Artículo',
                'slug' => 'article',
                'description' => 'Publicaciones teóricas, opiniones y contenido educativo de profundidad.',
                'icon' => 'book-open',
                'order' => 2,
            ],
            [
                'name' => 'Tutorial',
                'slug' => 'tutorial',
                'description' => 'Guías paso a paso con código, configuraciones y ejemplos prácticos.',
                'icon' => 'terminal',
                'order' => 3,
            ],
            [
                'name' => 'Anuncio',
                'slug' => 'announcement',
                'description' => 'Comunicados institucionales, avisos importantes y alertas del club.',
                'icon' => 'megaphone',
                'order' => 4,
            ],
            [
                'name' => 'Recurso',
                'slug' => 'resource',
                'description' => 'Material descargable, herramientas y assets para la comunidad.',
                'icon' => 'folder-down',
                'order' => 5,
            ],
            [
                'name' => 'Caso de Estudio',
                'slug' => 'case-study',
                'description' => 'Análisis de proyectos reales, retos superados y lecciones aprendidas.',
                'icon' => 'lightbulb',
                'order' => 6,
            ],
        ];

        foreach ($types as $type) {
            PostType::create($type);
        }
    }
}
