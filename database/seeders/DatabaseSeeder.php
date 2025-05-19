<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\EventSeeder;
use Database\Seeders\VisitSeeder;
use Database\Seeders\AmenitySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CulinarySeeder;
use Database\Seeders\DistrictSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\TourGuideSeeder;
use Database\Seeders\DestinationSeeder;
use Database\Seeders\RegistrationSeeder;
use Database\Seeders\AccommodationSeeder;
use Database\Seeders\TravelPackageSeeder;
use Database\Seeders\TransportationSeeder;
use Database\Seeders\CreativeEconomySeeder;
use Database\Seeders\CulturalHeritageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DistrictSeeder::class, // Tambahkan jika sudah dibuat
            CategorySeeder::class, // Tambahkan jika sudah dibuat
            DestinationSeeder::class,
            AccommodationSeeder::class,
            AmenitySeeder::class,
            TransportationSeeder::class,
            CulinarySeeder::class,
            CreativeEconomySeeder::class,
            ProductSeeder::class,
            CulturalHeritageSeeder::class,
            EventSeeder::class,
            RegistrationSeeder::class,
            TravelPackageSeeder::class,
            TourGuideSeeder::class,
            SettingsSeeder::class,
            VisitSeeder::class

        ]);
    }
}
