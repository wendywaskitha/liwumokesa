<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'Pariwisata Muna Barat',
                'type' => 'text',
                'display_name' => 'Nama Situs',
                'description' => 'Nama yang akan ditampilkan di header situs'
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'Jelajahi Pesona Muna Barat',
                'type' => 'text',
                'display_name' => 'Tagline Situs'
            ],
            [
                'group' => 'general',
                'key' => 'site_language',
                'value' => 'id',
                'type' => 'text',
                'display_name' => 'Bahasa Utama'
            ],

            // Website Settings
            [
                'group' => 'website',
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'display_name' => 'Mode Maintenance'
            ],
            [
                'group' => 'website',
                'key' => 'home_banner_title',
                'value' => 'Selamat Datang di Muna Barat',
                'type' => 'text',
                'display_name' => 'Judul Banner'
            ],

            // Contact Settings
            [
                'group' => 'contact',
                'key' => 'contact_email',
                'value' => 'contact@munabarat-tourism.test',
                'type' => 'text',
                'display_name' => 'Email Kontak'
            ],
            [
                'group' => 'contact',
                'key' => 'contact_phone',
                'value' => '+62 123 4567 890',
                'type' => 'text',
                'display_name' => 'Telepon Kontak'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                [
                    'group' => $setting['group'],
                    'key' => $setting['key']
                ],
                $setting
            );
        }
    }
}
