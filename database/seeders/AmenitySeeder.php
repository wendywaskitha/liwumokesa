<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Amenity::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil semua kecamatan
        $districts = District::all();

        if ($districts->isEmpty()) {
            $this->command->error('Tidak ada data kecamatan. Jalankan DistrictSeeder terlebih dahulu.');
            return;
        }

        // ATM/Bank
        $this->createAtmBanks($districts);

        // Fasilitas Kesehatan
        $this->createHealthFacilities($districts);

        // Tempat Ibadah
        $this->createWorshipPlaces($districts);

        // Toilet Umum
        $this->createPublicToilets($districts);

        // Pusat Informasi
        $this->createInfoCenters($districts);

        // SPBU/Pom Bensin
        $this->createGasStations($districts);

        // Pasar & Toko
        $this->createMarkets($districts);

        // Rest Area
        $this->createRestAreas($districts);

        $this->command->info('Berhasil menambahkan ' . Amenity::count() . ' fasilitas umum!');
    }

    /**
     * Membuat data ATM & Bank
     */
    private function createAtmBanks($districts)
    {
        $atmBanks = [
            [
                'name' => 'ATM Bank BRI Cabang Laworo',
                'type' => 'atm',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Poros Raha-Laworo, Desa Laworo, Kecamatan Lawa',
                'latitude' => -4.9482,
                'longitude' => 122.5685,
                'availability' => '24_hours',
                'is_free' => true,
                'is_accessible' => false,
                'features' => ['security'],
                'description' => 'ATM Bank BRI yang tersedia 24 jam. Melayani transaksi tunai, transfer, dan pembayaran.',
                'contact' => '14017',
                'status' => true,
            ],
            [
                'name' => 'Bank BNI Cabang Tampo',
                'type' => 'atm',
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Poros Tiworo, Desa Tampo, Kecamatan Tiworo Tengah',
                'latitude' => -4.9122,
                'longitude' => 122.4755,
                'availability' => 'custom',
                'opening_hours' => '08:00',
                'closing_hours' => '15:00',
                'operational_notes' => 'Tutup pada hari Sabtu dan Minggu',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'waiting_room', 'air_conditioner', 'security'],
                'description' => 'Bank BNI cabang Tampo yang melayani transaksi perbankan lengkap. Tersedia ATM 24 jam di depan kantor.',
                'contact' => '1500046',
                'status' => true,
            ],
            [
                'name' => 'ATM Bank Mandiri Tiworo',
                'type' => 'atm',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? $districts->random()->id,
                'address' => 'Terminal Tiworo, Kecamatan Tiworo Utara',
                'latitude' => -4.8955,
                'longitude' => 122.4911,
                'availability' => '24_hours',
                'is_free' => true,
                'is_accessible' => false,
                'features' => ['security'],
                'description' => 'ATM Bank Mandiri yang tersedia 24 jam di area terminal Tiworo.',
                'contact' => '14000',
                'status' => true,
            ],
        ];

        $this->createAmenities($atmBanks);
    }

    /**
     * Membuat data fasilitas kesehatan
     */
    private function createHealthFacilities($districts)
    {
        $healthFacilities = [
            [
                'name' => 'Puskesmas Lawa',
                'type' => 'health',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Kesehatan No. 15, Desa Lawa, Kecamatan Lawa',
                'latitude' => -4.9475,
                'longitude' => 122.5698,
                'availability' => 'custom',
                'opening_hours' => '07:30',
                'closing_hours' => '15:00',
                'operational_notes' => 'Unit Gawat Darurat buka 24 jam',
                'is_free' => false,
                'fee' => 10000,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'waiting_room', 'wifi'],
                'description' => 'Puskesmas yang melayani perawatan dasar dan rujukan. Memiliki fasilitas rawat inap untuk kasus ringan hingga sedang.',
                'contact' => '08123456789',
                'status' => true,
            ],
            [
                'name' => 'Klinik Pratama Tiworo',
                'type' => 'health',
                'district_id' => $districts->where('name', 'Tiworo Selatan')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Pelabuhan, Desa Tiworo, Kecamatan Tiworo Selatan',
                'latitude' => -4.9312,
                'longitude' => 122.4963,
                'availability' => 'custom',
                'opening_hours' => '08:00',
                'closing_hours' => '20:00',
                'is_free' => false,
                'fee' => 25000,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'waiting_room', 'air_conditioner'],
                'description' => 'Klinik swasta yang menyediakan layanan dokter umum dan gigi. Melayani tindakan medis ringan dan konsultasi kesehatan.',
                'contact' => '082345678901',
                'status' => true,
            ],
            [
                'name' => 'Apotek Sehat Barangka',
                'type' => 'health',
                'district_id' => $districts->where('name', 'Barangka')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Utama Barangka No. 45, Kecamatan Barangka',
                'latitude' => -4.9020,
                'longitude' => 122.5521,
                'availability' => 'custom',
                'opening_hours' => '08:00',
                'closing_hours' => '21:00',
                'operational_notes' => 'Buka setiap hari termasuk hari libur',
                'is_free' => false,
                'fee' => 0,
                'is_accessible' => true,
                'features' => ['parking', 'air_conditioner'],
                'description' => 'Apotek yang menyediakan obat-obatan lengkap dan konsultasi kefarmasian. Melayani resep dokter dan penjualan bebas.',
                'contact' => '085345678902',
                'status' => true,
            ],
        ];

        $this->createAmenities($healthFacilities);
    }

    /**
     * Membuat data tempat ibadah
     */
    private function createWorshipPlaces($districts)
    {
        $worshipPlaces = [
            [
                'name' => 'Masjid Agung Al-Ihsan',
                'type' => 'worship',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Masjid Agung, Desa Laworo, Kecamatan Lawa',
                'latitude' => -4.9490,
                'longitude' => 122.5705,
                'availability' => '24_hours',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'wifi'],
                'description' => 'Masjid terbesar di Kecamatan Lawa dengan kapasitas 1000 jamaah. Dilengkapi dengan tempat wudhu yang luas dan perpustakaan mini.',
                'contact' => '081234567890',
                'status' => true,
            ],
            [
                'name' => 'Gereja Santo Paulus',
                'type' => 'worship',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Pendidikan No. 12, Kecamatan Tiworo Utara',
                'latitude' => -4.8978,
                'longitude' => 122.4925,
                'availability' => 'custom',
                'opening_hours' => '06:00',
                'closing_hours' => '19:00',
                'operational_notes' => 'Misa setiap hari Minggu pukul 07:00 dan 17:00',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'waiting_room', 'air_conditioner'],
                'description' => 'Gereja Katolik dengan arsitektur modern. Ruang ibadah berkapasitas 300 orang dengan ruang pertemuan tambahan.',
                'contact' => '082345678903',
                'status' => true,
            ],
            [
                'name' => 'Pura Dharma Shanti',
                'type' => 'worship',
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id ?? $districts->random()->id,
                'address' => 'Desa Parida, Kecamatan Sawerigadi',
                'latitude' => -4.9178,
                'longitude' => 122.5324,
                'availability' => 'custom',
                'opening_hours' => '06:00',
                'closing_hours' => '18:00',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'toilet'],
                'description' => 'Pura Hindu dengan arsitektur Bali yang khas. Area sembahyang yang tenang dikelilingi taman dengan patung-patung dewa.',
                'contact' => '082345678904',
                'status' => true,
            ],
        ];

        $this->createAmenities($worshipPlaces);
    }

    /**
     * Membuat data toilet umum
     */
    private function createPublicToilets($districts)
    {
        $publicToilets = [
            [
                'name' => 'Toilet Umum Terminal Lawa',
                'type' => 'toilet',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Terminal Bus Lawa, Kecamatan Lawa',
                'latitude' => -4.9485,
                'longitude' => 122.5690,
                'availability' => 'custom',
                'opening_hours' => '05:00',
                'closing_hours' => '22:00',
                'is_free' => false,
                'fee' => 2000,
                'is_accessible' => false,
                'features' => [],
                'description' => 'Toilet umum standar di terminal bus dengan 4 bilik pria dan 4 bilik wanita.',
                'contact' => null,
                'status' => true,
            ],
            [
                'name' => 'Toilet Umum Pantai Napabale',
                'type' => 'toilet',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? $districts->random()->id,
                'address' => 'Pantai Napabale, Kecamatan Tiworo Utara',
                'latitude' => -4.9492,
                'longitude' => 122.5365,
                'availability' => 'custom',
                'opening_hours' => '07:00',
                'closing_hours' => '17:00',
                'operational_notes' => 'Tutup saat cuaca buruk',
                'is_free' => false,
                'fee' => 3000,
                'is_accessible' => false,
                'features' => [],
                'description' => 'Toilet umum di area wisata pantai. Dilengkapi dengan area bilas air tawar.',
                'contact' => null,
                'status' => true,
            ],
            [
                'name' => 'Toilet Umum & Kamar Mandi Pasar Tiworo',
                'type' => 'toilet',
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id ?? $districts->random()->id,
                'address' => 'Pasar Tradisional Tiworo, Kecamatan Tiworo Tengah',
                'latitude' => -4.9125,
                'longitude' => 122.4762,
                'availability' => 'custom',
                'opening_hours' => '05:00',
                'closing_hours' => '18:00',
                'is_free' => false,
                'fee' => 2000,
                'is_accessible' => false,
                'features' => [],
                'description' => 'Toilet dan kamar mandi umum di area pasar tradisional.',
                'contact' => null,
                'status' => true,
            ],
        ];

        $this->createAmenities($publicToilets);
    }

    /**
     * Membuat data pusat informasi
     */
    private function createInfoCenters($districts)
    {
        $infoCenters = [
            [
                'name' => 'Pusat Informasi Wisata Muna Barat',
                'type' => 'information',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Poros Raha-Laworo km 2, Kecamatan Lawa',
                'latitude' => -4.9468,
                'longitude' => 122.5663,
                'availability' => 'custom',
                'opening_hours' => '08:00',
                'closing_hours' => '16:00',
                'operational_notes' => 'Tutup pada hari Minggu dan hari libur nasional',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'wifi', 'waiting_room', 'air_conditioner'],
                'description' => 'Pusat informasi wisata utama di Muna Barat. Menyediakan peta, brosur, dan pemandu wisata. Tersedia juga area pameran budaya lokal.',
                'contact' => '085123456789',
                'status' => true,
            ],
            [
                'name' => 'Balai Desa Tiworo - Informasi Wisata',
                'type' => 'information',
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id ?? $districts->random()->id,
                'address' => 'Kantor Balai Desa Tiworo, Kecamatan Tiworo Tengah',
                'latitude' => -4.9130,
                'longitude' => 122.4758,
                'availability' => 'custom',
                'opening_hours' => '08:00',
                'closing_hours' => '15:00',
                'operational_notes' => 'Tutup pada hari Sabtu dan Minggu',
                'is_free' => true,
                'is_accessible' => false,
                'features' => ['parking', 'toilet', 'waiting_room'],
                'description' => 'Pojok informasi wisata di Balai Desa Tiworo. Menyediakan informasi tentang wisata kepulauan Tiworo dan transportasi antar pulau.',
                'contact' => '082234567890',
                'status' => true,
            ],
        ];

        $this->createAmenities($infoCenters);
    }

    /**
     * Membuat data SPBU/Pom Bensin
     */
    private function createGasStations($districts)
    {
        $gasStations = [
            [
                'name' => 'SPBU Pertamina 74.982.01',
                'type' => 'gas_station',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Poros Raha-Laworo km 5, Kecamatan Lawa',
                'latitude' => -4.9472,
                'longitude' => 122.5710,
                'availability' => '24_hours',
                'is_free' => false,
                'fee' => 0,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'air_conditioner', 'waiting_room', 'security'],
                'description' => 'SPBU Pertamina yang melayani Pertalite, Pertamax, Dexlite, dan Pertamina Dex. Dilengkapi dengan mini market dan ATM.',
                'contact' => '(0401) 3211234',
                'status' => true,
            ],
            [
                'name' => 'SPBU Mini Tiworo',
                'type' => 'gas_station',
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Pelabuhan, Kecamatan Tiworo Tengah',
                'latitude' => -4.9120,
                'longitude' => 122.4765,
                'availability' => 'custom',
                'opening_hours' => '07:00',
                'closing_hours' => '21:00',
                'is_free' => false,
                'fee' => 0,
                'is_accessible' => false,
                'features' => ['parking'],
                'description' => 'SPBU mini yang menyediakan Pertalite dan Pertamax. Tersedia juga oli dan kebutuhan kendaraan dasar.',
                'contact' => '085234567890',
                'status' => true,
            ],
        ];

        $this->createAmenities($gasStations);
    }

    /**
     * Membuat data Pasar & Toko
     */
    private function createMarkets($districts)
    {
        $markets = [
            [
                'name' => 'Pasar Tradisional Lawa',
                'type' => 'market',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Pasar Lama, Kecamatan Lawa',
                'latitude' => -4.9482,
                'longitude' => 122.5670,
                'availability' => 'custom',
                'opening_hours' => '05:00',
                'closing_hours' => '12:00',
                'operational_notes' => 'Paling ramai pada pagi hari',
                'is_free' => true,
                'is_accessible' => false,
                'features' => ['parking', 'toilet'],
                'description' => 'Pasar tradisional terbesar di Muna Barat. Menjual hasil laut segar, sayuran, buah-buahan, dan kebutuhan sehari-hari.',
                'contact' => null,
                'status' => true,
            ],
            [
                'name' => 'Tiworo Mart',
                'type' => 'market',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Poros Tiworo No. 15, Kecamatan Tiworo Utara',
                'latitude' => -4.8965,
                'longitude' => 122.4930,
                'availability' => 'custom',
                'opening_hours' => '07:00',
                'closing_hours' => '22:00',
                'operational_notes' => 'Buka setiap hari',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'air_conditioner'],
                'description' => 'Mini market modern yang menyediakan kebutuhan sehari-hari, makanan, minuman, dan oleh-oleh khas Muna Barat.',
                'contact' => '082345678905',
                'status' => true,
            ],
            [
                'name' => 'Toko Souvenir Barangka',
                'type' => 'market',
                'district_id' => $districts->where('name', 'Barangka')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Raya Barangka No. 8, Kecamatan Barangka',
                'latitude' => -4.9022,
                'longitude' => 122.5525,
                'availability' => 'custom',
                'opening_hours' => '09:00',
                'closing_hours' => '17:00',
                'operational_notes' => 'Tutup pada hari Senin',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'air_conditioner', 'wifi'],
                'description' => 'Toko yang menjual berbagai souvenir dan kerajinan khas Muna Barat. Tersedia kerajinan tenun, ukiran kayu, dan perhiasan lokal.',
                'contact' => '081234567891',
                'status' => true,
            ],
        ];

        $this->createAmenities($markets);
    }

    /**
     * Membuat data Rest Area
     */
    private function createRestAreas($districts)
    {
        $restAreas = [
            [
                'name' => 'Rest Area Km 10',
                'type' => 'rest_area',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? $districts->random()->id,
                'address' => 'Jl. Poros Raha-Laworo km 10, Kecamatan Lawa',
                'latitude' => -4.9510,
                'longitude' => 122.5750,
                'availability' => '24_hours',
                'is_free' => true,
                'is_accessible' => true,
                'features' => ['parking', 'toilet', 'waiting_room', 'charging'],
                'description' => 'Area istirahat dengan fasilitas standar. Tersedia kedai kopi dan warung makan lokal.',
                'contact' => null,
                'status' => true,
            ],
            [
                'name' => 'Rest Area Pantai Napabale',
                'type' => 'rest_area',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? $districts->random()->id,
                'address' => 'Pantai Napabale, Kecamatan Tiworo Utara',
                'latitude' => -4.9495,
                'longitude' => 122.5370,
                'availability' => 'custom',
                'opening_hours' => '07:00',
                'closing_hours' => '18:00',
                'operational_notes' => 'Tutup saat cuaca buruk',
                'is_free' => true,
                'is_accessible' => false,
                'features' => ['parking', 'toilet', 'waiting_room'],
                'description' => 'Area istirahat di kawasan wisata Pantai Napabale dengan pemandangan pantai. Tersedia gazebo dan warung makan.',
                'contact' => '082345678906',
                'status' => true,
            ],
        ];

        $this->createAmenities($restAreas);
    }

    /**
     * Helper function untuk membuat data amenities
     */
    private function createAmenities($amenities)
    {
        foreach ($amenities as $amenity) {
            $slug = Str::slug($amenity['name']);
            Amenity::create(array_merge($amenity, ['slug' => $slug]));
            $this->command->info("Berhasil menambahkan fasilitas: {$amenity['name']}");
        }
    }
}
