<?php

namespace Database\Seeders\Forms;

use App\Models\Forms\FormType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Examen',
                'slug' => 'quiz',
                'description' => 'Evaluación con respuestas correctas y calificación automática. Soporta tiempo límite e intentos.',
                'icon' => 'graduation-cap',
                'color' => '#4f46e5', // Indigo 600
                'is_gradable' => true,
                'order' => 1,
            ],
            [
                'name' => 'Evaluación',
                'slug' => 'evaluation',
                'description' => 'Evaluación formal de desempeño basada en rúbricas o criterios de maestría.',
                'icon' => 'clipboard-check',
                'color' => '#dc2626', // Red 600
                'is_gradable' => true,
                'order' => 2,
            ],
            [
                'name' => 'Diagnóstico',
                'slug' => 'diagnostic',
                'description' => 'Test de nivel inicial para identificar conocimientos previos antes de un curso.',
                'icon' => 'flask-conical',
                'color' => '#0284c7', // Sky 600
                'is_gradable' => true,
                'order' => 3,
            ],
            [
                'name' => 'Autoevaluación',
                'slug' => 'self-assessment',
                'description' => 'El alumno reflexiona y evalúa su propio progreso y comprensión.',
                'icon' => 'user-round-cog',
                'color' => '#0d9488', // Teal 600
                'is_gradable' => true,
                'order' => 4,
            ],
            [
                'name' => 'Revisión por Pares',
                'slug' => 'peer-review',
                'description' => 'Evaluación cruzada entre alumnos usando rúbricas compartidas.',
                'icon' => 'users-round',
                'color' => '#ea580c', // Orange 600
                'is_gradable' => true,
                'order' => 5,
            ],
            [
                'name' => 'Encuesta',
                'slug' => 'survey',
                'description' => 'Recolección de datos o satisfacción. No posee respuestas correctas.',
                'icon' => 'list-todo',
                'color' => '#2563eb', // Blue 600
                'is_gradable' => false,
                'order' => 6,
            ],
            [
                'name' => 'Votación Rapida',
                'slug' => 'poll',
                'description' => 'Sondeo de una sola pregunta para decisiones rápidas en tiempo real.',
                'icon' => 'vote',
                'color' => '#7c3aed', // Violet 600
                'is_gradable' => false,
                'order' => 7,
            ],
            [
                'name' => 'Retroalimentación',
                'slug' => 'feedback',
                'description' => 'Comentarios estructurados post-actividad para mejora continua.',
                'icon' => 'message-square-plus',
                'color' => '#16a34a', // Green 600
                'is_gradable' => false,
                'order' => 8,
            ],
            [
                'name' => 'Investigación',
                'slug' => 'research',
                'description' => 'Instrumento académico para recolección de variables complejas.',
                'icon' => 'microscope',
                'color' => '#9333ea', // Purple 600
                'is_gradable' => false,
                'order' => 9,
            ],
            [
                'name' => 'Registro / Inscripción',
                'slug' => 'registration',
                'description' => 'Captura de datos para participación en eventos o actividades.',
                'icon' => 'contact-2',
                'color' => '#ca8a04', // Yellow 600
                'is_gradable' => false,
                'order' => 10,
            ],
            [
                'name' => 'Contacto',
                'slug' => 'contact',
                'description' => 'Canal de comunicación directo para soporte o dudas.',
                'icon' => 'mail',
                'color' => '#475569', // Slate 600
                'is_gradable' => false,
                'order' => 11,
            ],
            [
                'name' => 'Personalizado',
                'slug' => 'custom',
                'description' => 'Formulario sin comportamiento predefinido para usos específicos.',
                'icon' => 'layout-grid',
                'color' => '#64748b', // Slate 500
                'is_gradable' => false,
                'order' => 12,
            ],
        ];

        foreach ($types as $type) {
            FormType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
