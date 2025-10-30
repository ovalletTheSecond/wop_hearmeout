<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Aucune catégorie',
            'Male',
            'Femelle',
            'Autre Genre',
            'Anime/Cartoons',
            // Suggestions pop culture
            'Films & Séries',
            'Jeux Vidéo',
            'Comics & BD',
            'K-Pop/J-Pop',
            'Musique',
            'Sport',
            'Livres',
            'Célébrités',
            'YouTubers/Streamers',
            'Personnages Historiques',
            'Animaux/Mascottes',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );
        }
    }
}
