<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            'Sawerigadi',
            'Barangka',
            'Lawa',
            'Wadaga',
            'Kusambi',
            'Tiworo Kepulauan',
            'Tiworo Selatan',
            'Tiworo Tengah',
            'Tiworo Utara',
            'Maginti',
            'Napano Kusambi',
        ];

        foreach ($districts as $district) {
            District::create([
                'name' => $district,
                'slug' => Str::slug($district),
                'description' => "Kecamatan {$district} adalah salah satu kecamatan di Kabupaten Muna Barat, Sulawesi Tenggara.",
                'featured_image' => null,
            ]);
        }
    }
}
