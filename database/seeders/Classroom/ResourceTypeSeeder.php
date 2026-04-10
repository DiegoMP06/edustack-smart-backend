<?php

namespace Database\Seeders\Classroom;

use App\Models\Classroom\ResourceType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Video',
                'slug' => 'video',
                'icon' => 'video',
                'color' => '#dc2626', // Red 600 (Asociado comúnmente a video/YouTube)
            ],
            [
                'name' => 'Documento PDF',
                'slug' => 'pdf',
                'icon' => 'file-text',
                'color' => '#e11d48', // Rose 600
            ],
            [
                'name' => 'Enlace externo',
                'slug' => 'link',
                'icon' => 'external-link',
                'color' => '#2563eb', // Blue 600
            ],
            [
                'name' => 'Archivo Descargable',
                'slug' => 'file',
                'icon' => 'download-cloud',
                'color' => '#475569', // Slate 600
            ],
            [
                'name' => 'Presentación',
                'slug' => 'presentation',
                'icon' => 'presentation',
                'color' => '#ea580c', // Orange 600
            ],
            [
                'name' => 'Código Fuente',
                'slug' => 'code',
                'icon' => 'terminal',
                'color' => '#059669', // Emerald 600
            ],
            [
                'name' => 'Imagen',
                'slug' => 'image',
                'icon' => 'image',
                'color' => '#8b5cf6', // Violet 600
            ],
            [
                'name' => 'Audio',
                'slug' => 'audio',
                'icon' => 'headphones',
                'color' => '#0891b2', // Cyan 600
            ],
        ];

        foreach ($types as $type) {
            ResourceType::create($type);
        }
    }
}
