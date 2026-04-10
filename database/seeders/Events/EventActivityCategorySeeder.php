<?php

namespace Database\Seeders\Events;

use App\Models\Events\EventActivityCategory;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventActivityCategorySeeder extends Seeder
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
                'description' => 'Talleres de frameworks modernos, maquetación avanzada y performance web.',
                'color' => '#2563eb', // Blue 600
                'order' => 1,
            ],
            [
                'name' => 'Desarrollo de Software',
                'slug' => 'software-development',
                'description' => 'Sesiones sobre lenguajes de backend, algoritmos y paradigmas de programación.',
                'color' => '#4f46e5', // Indigo 600
                'order' => 2,
            ],
            [
                'name' => 'Ciberseguridad',
                'slug' => 'cybersecurity',
                'description' => 'Competencias CTF, análisis de vulnerabilidades y seguridad en redes.',
                'color' => '#dc2626', // Red 600
                'order' => 3,
            ],
            [
                'name' => 'Inteligencia Artificial',
                'slug' => 'artificial-intelligence',
                'description' => 'Entrenamiento de modelos, procesamiento de lenguaje natural y visión por computadora.',
                'color' => '#7c3aed', // Violet 600
                'order' => 4,
            ],
            [
                'name' => 'Redes e Infraestructura',
                'slug' => 'networking-and-infrastructure',
                'description' => 'Configuración de hardware de red, protocolos y topologías físicas.',
                'color' => '#0284c7', // Sky 600
                'order' => 5,
            ],
            [
                'name' => 'Ciencia de Datos',
                'slug' => 'data-science',
                'description' => 'Análisis estadístico, visualización de datos y minería de información.',
                'color' => '#0891b2', // Cyan 600
                'order' => 6,
            ],
            [
                'name' => 'DevOps y Automatización',
                'slug' => 'devops-and-automation',
                'description' => 'Implementación de pipelines CI/CD y automatización de flujos de trabajo.',
                'color' => '#ea580c', // Orange 600
                'order' => 7,
            ],
            [
                'name' => 'Cloud Computing',
                'slug' => 'cloud-computing',
                'description' => 'Gestión de servicios en la nube (AWS, Azure, GCP) y serverless.',
                'color' => '#0ea5e9', // Light Blue
                'order' => 8,
            ],
            [
                'name' => 'Internet de las Cosas (IoT)',
                'slug' => 'internet-of-things',
                'description' => 'Prototipado con Arduino, Raspberry Pi y protocolos de comunicación inalámbrica.',
                'color' => '#059669', // Emerald 600
                'order' => 9,
            ],
            [
                'name' => 'Blockchain y Web3',
                'slug' => 'blockchain-and-web3',
                'description' => 'Desarrollo de Smart Contracts, dApps y tecnologías descentralizadas.',
                'color' => '#d97706', // Amber 600
                'order' => 10,
            ],
            [
                'name' => 'Desarrollo Móvil',
                'slug' => 'mobile-development',
                'description' => 'Diseño y despliegue de aplicaciones para dispositivos móviles.',
                'color' => '#16a34a', // Green 600
                'order' => 11,
            ],
            [
                'name' => 'Desarrollo de Videojuegos',
                'slug' => 'game-development',
                'description' => 'Uso de motores gráficos, diseño de niveles y mecánicas de juego.',
                'color' => '#9333ea', // Purple 600
                'order' => 12,
            ],
            [
                'name' => 'Diseño UI/UX',
                'slug' => 'ui-ux-design',
                'description' => 'Metodologías de diseño centrado en el usuario y prototipado interactivo.',
                'color' => '#db2777', // Pink 600
                'order' => 13,
            ],
            [
                'name' => 'Calidad de Software (QA)',
                'slug' => 'quality-assurance',
                'description' => 'Pruebas automatizadas, unitarias y aseguramiento de la calidad.',
                'color' => '#0d9488', // Teal 600
                'order' => 14,
            ],
            [
                'name' => 'Habilidades Blandas',
                'slug' => 'soft-skills',
                'description' => 'Taller de oratoria, liderazgo de equipos y marca personal para ingenieros.',
                'color' => '#65a30d', // Lime 600
                'order' => 15,
            ],
            [
                'name' => 'Gestión de Proyectos TI',
                'slug' => 'it-management',
                'description' => 'Planificación estratégica y metodologías de gestión técnica.',
                'color' => '#475569', // Slate 600
                'order' => 16,
            ],
            [
                'name' => 'Cultura Open Source',
                'slug' => 'open-source-culture',
                'description' => 'Contribución a proyectos comunitarios y licencias de software libre.',
                'color' => '#15803d', // Dark Green
                'order' => 17,
            ],
        ];

        foreach ($categories as $category) {
            EventActivityCategory::create($category);
        }
    }
}
