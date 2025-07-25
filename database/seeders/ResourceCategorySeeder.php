<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResourceCategory;
use Illuminate\Support\Str;

class ResourceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Prédications',
                'description' => 'Messages et sermons audio/vidéo',
                'color' => '#3B82F6',
                'icon' => 'microphone',
                'order' => 1,
            ],
            [
                'name' => 'Études bibliques',
                'description' => 'Cours et études bibliques approfondies',
                'color' => '#10B981',
                'icon' => 'book-open',
                'order' => 2,
            ],
            [
                'name' => 'Livres et documents',
                'description' => 'Livres, articles et documents PDF',
                'color' => '#F59E0B',
                'icon' => 'document-text',
                'order' => 3,
            ],
            [
                'name' => 'Musique et louange',
                'description' => 'Chants, hymnes et musique chrétienne',
                'color' => '#8B5CF6',
                'icon' => 'musical-note',
                'order' => 4,
            ],
            [
                'name' => 'Témoignages',
                'description' => 'Témoignages de vie et conversions',
                'color' => '#EF4444',
                'icon' => 'heart',
                'order' => 5,
            ],
            [
                'name' => 'Formation',
                'description' => 'Matériel de formation et de développement',
                'color' => '#06B6D4',
                'icon' => 'academic-cap',
                'order' => 6,
            ],
            [
                'name' => 'Liens utiles',
                'description' => 'Sites web et ressources en ligne',
                'color' => '#84CC16',
                'icon' => 'link',
                'order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            ResourceCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category
            );
        }
    }
}
