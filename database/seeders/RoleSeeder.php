<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin role
        Role::create([
            'name' => 'admin',
            'description' => 'Administrator dengan akses penuh ke semua fitur panel admin',
            'permissions' => json_encode([
                'manage_users',
                'manage_destinations',
                'manage_accommodations',
                'manage_transportations',
                'manage_culinaries',
                'manage_creative_economies',
                'manage_cultural_heritages',
                'manage_events',
                'manage_travel_packages',
                'manage_districts',
                'manage_categories',
                'manage_amenities',
                'manage_reviews',
                'manage_galleries',
                'manage_settings',
                'view_statistics',
            ]),
        ]);

        // Wisatawan role
        Role::create([
            'name' => 'wisatawan',
            'description' => 'Pengguna reguler yang dapat mengakses fitur wisatawan',
            'permissions' => json_encode([
                'view_profile',
                'edit_profile',
                'manage_wishlists',
                'manage_bookings',
                'manage_reviews',
                'manage_itineraries',
                'view_destinations',
                'view_accommodations',
                'view_transportations',
                'view_culinaries',
                'view_creative_economies',
                'view_cultural_heritages',
                'view_events',
                'view_travel_packages',
            ]),
        ]);
    }
}
