<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get destinations and users
        $destinations = Destination::all();
        $users = User::all();

        // Exit if no destinations
        if ($destinations->isEmpty()) {
            $this->command->info('No destinations found. Please run DestinationSeeder first.');
            return;
        }

        // Create random visits over the last 90 days
        $visitCount = 0;
        $startDate = now()->subDays(90);
        $endDate = now();

        // For each destination, create random visits
        foreach ($destinations as $destination) {
            // Random number of visits per destination (10-100)
            $visits = $faker->numberBetween(10, 100);

            for ($i = 0; $i < $visits; $i++) {
                // Random date between start and end date
                $visitDate = $faker->dateTimeBetween($startDate, $endDate);

                // 70% chance to be anonymous, 30% to be logged in
                $userId = $faker->boolean(30) ? $users->random()->id : null;

                Visit::create([
                    'destination_id' => $destination->id,
                    'user_id' => $userId,
                    'visit_date' => $visitDate,
                    'created_at' => $visitDate,
                    'updated_at' => $visitDate,
                    'ip_address' => $faker->ipv4,
                    'user_agent' => $faker->userAgent,
                    'referrer' => $faker->randomElement([
                        'https://google.com',
                        'https://facebook.com',
                        'https://instagram.com',
                        'https://munabarat.go.id',
                        null
                    ]),
                    'visit_type' => 'destination',
                ]);

                $visitCount++;
            }

            // Add some page visits too
            $pageVisits = $faker->numberBetween(5, 30);

            for ($i = 0; $i < $pageVisits; $i++) {
                $visitDate = $faker->dateTimeBetween($startDate, $endDate);
                $userId = $faker->boolean(20) ? $users->random()->id : null;

                Visit::create([
                    'user_id' => $userId,
                    'visit_date' => $visitDate,
                    'created_at' => $visitDate,
                    'updated_at' => $visitDate,
                    'ip_address' => $faker->ipv4,
                    'user_agent' => $faker->userAgent,
                    'page_visited' => $faker->randomElement([
                        'home',
                        'about',
                        'contact',
                        'destinations',
                        'events',
                        'packages',
                    ]),
                    'visit_type' => 'page',
                ]);

                $visitCount++;
            }
        }

        $this->command->info("Created {$visitCount} visit records.");
    }
}
