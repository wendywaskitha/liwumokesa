<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Destination categories
        $destinationCategories = [
            'Pantai' => 'beach',
            'Air Terjun' => 'waterfall',
            'Bukit' => 'mountain',
            'Sejarah' => 'historical',
            'Religi' => 'religious',
            'Agrowisata' => 'agro',
            'Pulau' => 'island',
            'Budaya' => 'cultural',
        ];

        foreach ($destinationCategories as $name => $icon) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'destinasi',
                'icon' => $icon,
                'description' => "Kategori {$name} untuk destinasi wisata di Kabupaten Muna Barat",
            ]);
        }

        // Culinary categories
        $culinaryCategories = [
            'Seafood' => 'seafood',
            'Tradisional' => 'traditional',
            'Modern' => 'modern',
            'Kafe' => 'cafe',
            'Warung' => 'food-stall',
            'Restoran' => 'restaurant',
        ];

        foreach ($culinaryCategories as $name => $icon) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'kuliner',
                'icon' => $icon,
                'description' => "Kategori {$name} untuk kuliner di Kabupaten Muna Barat",
            ]);
        }

        // Creative economy categories
        $creativeCategories = [
            'Kerajinan Tangan' => 'handicraft',
            'Tenun' => 'weaving',
            'Ukiran' => 'carving',
            'Kuliner Khas' => 'food',
            'Fesyen' => 'fashion',
            'Souvenir' => 'souvenir',
        ];

        foreach ($creativeCategories as $name => $icon) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'ekonomi-kreatif',
                'icon' => $icon,
                'description' => "Kategori {$name} untuk ekonomi kreatif di Kabupaten Muna Barat",
            ]);
        }
    }
}
