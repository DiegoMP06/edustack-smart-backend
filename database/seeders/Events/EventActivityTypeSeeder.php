<?php

namespace Database\Seeders\Events;

use App\Enums\Events\BehaviorType;
use App\Models\Events\EventActivityType;
use Illuminate\Database\Seeder;

class EventActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Hackathon',
                'slug' => 'hackathon',
                'behavior_type' => BehaviorType::COMPETITION->value,
                'description' => 'Maratón de programación por equipos con rondas, retos y premiación.',
                'icon' => 'trophy',
                'order' => 1,
            ],
            [
                'name' => 'Competencia',
                'slug' => 'competition',
                'behavior_type' => BehaviorType::COMPETITION->value,
                'description' => 'Actividad competitiva individual o por equipos con rondas clasificatorias.',
                'icon' => 'medal',
                'order' => 2,
            ],
            [
                'name' => 'Bootcamp',
                'slug' => 'bootcamp',
                'behavior_type' => BehaviorType::BOOTCAMP->value,
                'description' => 'Programa intensivo vinculado a rutas de aprendizaje en la plataforma.',
                'icon' => 'graduation-cap',
                'order' => 3,
            ],
            [
                'name' => 'Taller',
                'slug' => 'workshop',
                'behavior_type' => BehaviorType::WORKSHOP->value,
                'description' => 'Sesión práctica guiada por un instructor con ejercicios y materiales directos.',
                'icon' => 'hammer',
                'order' => 4,
            ],
            [
                'name' => 'Conferencia',
                'slug' => 'conference',
                'behavior_type' => BehaviorType::TALK->value,
                'description' => 'Presentación formal con ponentes expertos sobre tendencias de industria.',
                'icon' => 'presentation',
                'order' => 5,
            ],
            [
                'name' => 'Charla',
                'slug' => 'development-talk',
                'behavior_type' => BehaviorType::TALK->value,
                'description' => 'Conversación técnica informal para compartir experiencias y networking.',
                'icon' => 'messages-square',
                'order' => 6,
            ],
            [
                'name' => 'Webinar de IA',
                'slug' => 'ai-webinar',
                'behavior_type' => BehaviorType::TALK->value,
                'description' => 'Seminario online sobre inteligencia artificial y computación avanzada.',
                'icon' => 'cpu',
                'order' => 7,
            ],
            [
                'name' => 'Contribución Open Source',
                'slug' => 'open-source-contribution',
                'behavior_type' => BehaviorType::OPEN_SOURCE->value,
                'description' => 'Sesión colaborativa para aportar a repositorios públicos y proyectos de comunidad.',
                'icon' => 'git-pull-request',
                'order' => 8,
            ],
            [
                'name' => 'Demostración (Demo Day)',
                'slug' => 'demo-day',
                'behavior_type' => BehaviorType::DEMO->value,
                'description' => 'Exposición pública de proyectos y MVPs desarrollados por los miembros.',
                'icon' => 'monitor-play',
                'order' => 9,
            ],
            [
                'name' => 'Revisión de Código',
                'slug' => 'code-review-party',
                'behavior_type' => BehaviorType::CODE_REVIEW->value,
                'description' => 'Análisis colectivo y retroalimentación técnica para mejorar la calidad del código.',
                'icon' => 'search-code',
                'order' => 10,
            ],
        ];

        foreach ($types as $type) {
            EventActivityType::create($type);
        }
    }
}
