<?php

namespace Database\Seeders;

use App\Models\Culinary;
use App\Models\District;
use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CulinarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang mungkin sudah ada untuk mencegah duplikasi
        DB::table('culinaries')->truncate();

        // Ambil semua kecamatan untuk referensi
        $districts = District::all();

        // Data kuliner khas Muna Barat
        $culinaries = [
            [
                'name' => 'Warung Seafood Pak Mahmud',
                'slug' => 'warung-seafood-pak-mahmud',
                'type' => 'warung', // Mengubah dari 'seafood' ke 'warung' untuk menyesuaikan dengan ENUM
                'description' => 'Warung Seafood Pak Mahmud menyajikan hidangan laut segar yang ditangkap langsung oleh nelayan lokal Muna Barat. Spesialisasinya adalah ikan bakar dengan bumbu khas Muna yang menggunakan rempah-rempah lokal seperti sereh, kunyit, dan daun kemangi. Tempat makan ini sangat populer di kalangan penduduk lokal dan wisatawan karena hidangannya yang autentik dan harganya yang terjangkau. Suasana warung yang sederhana namun bersih dengan pemandangan laut menambah kenikmatan bersantap di sini.',
                'address' => 'Jl. Pantai Pajala No. 15, Desa Pajala, Kec. Tiworo Tengah',
                'latitude' => -4.9843,
                'longitude' => 122.5213,
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id,
                'price_range_start' => 25000,
                'price_range_end' => 100000,
                'opening_hours' => '10:00-21:00',
                'contact_person' => 'Pak Mahmud',
                'phone_number' => '082345678123',
                'featured_image' => null,
                'status' => true,
                'social_media' => '@seafoodpakmahmud',
                'has_vegetarian_option' => false,
                'halal_certified' => true,
                'has_delivery' => true,
                'featured_menu' => json_encode([
                    'Ikan Bakar Bumbu Muna',
                    'Cumi Saus Padang',
                    'Udang Bakar Madu',
                    'Sup Ikan Kuah Asam',
                    'Plecing Kangkung'
                ]),
                'is_recommended' => true
            ],
            [
                'name' => 'Rumah Makan Ibu Siti',
                'slug' => 'rumah-makan-ibu-siti',
                'type' => 'tradisional',
                'description' => 'Rumah Makan Ibu Siti merupakan tempat makan legendaris di Muna Barat yang telah berdiri sejak tahun 1980-an. Menu andalannya adalah masakan rumahan khas Sulawesi Tenggara dengan sentuhan resep keluarga yang diturunkan dari generasi ke generasi. Pengunjung dapat menikmati makanan dalam suasana rumah panggung tradisional yang sejuk dan nyaman. Ibu Siti sendiri sering menyambut tamu-tamunya dan berbagi cerita tentang makanan tradisional Muna.',
                'address' => 'Jl. Raya Sawerigadi No. 42, Kec. Sawerigadi',
                'latitude' => -4.9758,
                'longitude' => 122.4930,
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id,
                'price_range_start' => 15000,
                'price_range_end' => 50000,
                'opening_hours' => '07:00-20:00',
                'contact_person' => 'Ibu Siti',
                'phone_number' => '085678901234',
                'featured_image' => null,
                'status' => true,
                'social_media' => '@rumahmakansiti',
                'has_vegetarian_option' => true,
                'halal_certified' => true,
                'has_delivery' => false,
                'featured_menu' => json_encode([
                    'Kasuami',
                    'Ikan Kuah Kuning',
                    'Sayur Bening Kangkung',
                    'Ayam Panggang Bumbu Muna',
                    'Dabu-dabu Ikan Rica'
                ]),
                'is_recommended' => true
            ],
            [
                'name' => 'Cafe Pantai Indah',
                'slug' => 'cafe-pantai-indah',
                'type' => 'kafe',
                'description' => 'Cafe Pantai Indah adalah tempat nongkrong modern dengan konsep beachfront cafe yang menawarkan pemandangan laut langsung dari teras cafe. Menu yang ditawarkan adalah perpaduan masakan lokal dan internasional dengan sentuhan kreatif. Cafe ini sering menjadi spot favorit untuk menikmati sunset sambil menyeruput kopi lokal Muna atau minuman khas lainnya. Desain interior yang instagramable dengan elemen kayu, bambu, dan aksen warna pastel menjadikannya spot foto yang menarik.',
                'address' => 'Jl. Pantai Tasipi No. 8, Kec. Tiworo Utara',
                'latitude' => -4.9620,
                'longitude' => 122.5530,
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id,
                'price_range_start' => 20000,
                'price_range_end' => 75000,
                'opening_hours' => '09:00-22:00',
                'contact_person' => 'Bapak Andi',
                'phone_number' => '081234987654',
                'featured_image' => null,
                'status' => true,
                'social_media' => '@cafepantaiindah',
                'has_vegetarian_option' => true,
                'halal_certified' => true,
                'has_delivery' => true,
                'featured_menu' => json_encode([
                    'Kopi Muna',
                    'Pisang Epe',
                    'Roti Bakar Special',
                    'Nasi Goreng Seafood',
                    'Mocktail Buah Lokal'
                ]),
                'is_recommended' => false
            ],
            [
                'name' => 'Warung Bajo "Nelayan"',
                'slug' => 'warung-bajo-nelayan',
                'type' => 'warung',
                'description' => 'Warung Bajo "Nelayan" adalah warung autentik yang dikelola oleh keluarga suku Bajo di kawasan Pulau Mola. Hidangan seafood yang disajikan dijamin segar karena ditangkap langsung oleh nelayan Bajo setiap pagi. Warung ini menawarkan pengalaman kuliner unik dengan menu tradisional suku Bajo yang jarang ditemui di tempat lain. Pengunjung dapat menikmati makan di atas rumah panggung yang berada di atas laut, memberikan pengalaman makan yang tak terlupakan dengan pemandangan laut 360 derajat.',
                'address' => 'Pulau Mola, Kec. Tiworo Kepulauan',
                'latitude' => -5.0340,
                'longitude' => 122.5020,
                'district_id' => $districts->where('name', 'Tiworo Kepulauan')->first()->id,
                'price_range_start' => 30000,
                'price_range_end' => 150000,
                'opening_hours' => '11:00-20:00',
                'contact_person' => 'La Ode Mali',
                'phone_number' => '085678123456',
                'featured_image' => null,
                'status' => true,
                'social_media' => null,
                'has_vegetarian_option' => false,
                'halal_certified' => true,
                'has_delivery' => false,
                'featured_menu' => json_encode([
                    'Ikan Parende Bakar',
                    'Sate Gurita',
                    'Kerang Rebus Bajo',
                    'Cumi Hitam',
                    'Sup Ikan Kerapu'
                ]),
                'is_recommended' => true
            ],
            [
                'name' => 'Restoran Tiworo Grand',
                'slug' => 'restoran-tiworo-grand',
                'type' => 'restoran',
                'description' => 'Restoran Tiworo Grand adalah restoran mewah yang terletak di hotel utama di Muna Barat. Dengan interior berkelas dan pelayanan profesional, restoran ini menawarkan pengalaman fine dining dengan menu fusion antara masakan Sulawesi dan internasional. Chef berpengalaman menggabungkan bahan-bahan lokal premium dengan teknik memasak internasional. Restoran ini cocok untuk acara spesial atau jamuan bisnis. Tersedia ruang privat untuk acara khusus dengan kapasitas hingga 20 orang.',
                'address' => 'Jl. Poros Raha-Wakatobi Km 10, Kec. Tiworo Tengah',
                'latitude' => -4.9760,
                'longitude' => 122.5230,
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id,
                'price_range_start' => 75000,
                'price_range_end' => 250000,
                'opening_hours' => '12:00-23:00',
                'contact_person' => 'Manager Restoran',
                'phone_number' => '082187654321',
                'featured_image' => null,
                'status' => true,
                'social_media' => '@tiworogrand',
                'has_vegetarian_option' => true,
                'halal_certified' => true,
                'has_delivery' => true,
                'featured_menu' => json_encode([
                    'Lobster Panggang Saus Kecombrang',
                    'Ikan Kakap Merah Steam Hongkong',
                    'Bebek Crispy Saus Mangga',
                    'Sup Konro',
                    'Pavlova Buah Tropis'
                ]),
                'is_recommended' => true
            ],
            [
                'name' => 'Warung Kasuami Bu Intan',
                'slug' => 'warung-kasuami-bu-intan',
                'type' => 'warung',
                'description' => 'Warung Kasuami Bu Intan adalah warung kecil yang khusus menyajikan kasuami, makanan pokok tradisional Muna yang terbuat dari singkong parut yang dikukus. Kasuami disajikan dengan berbagai lauk pendamping tradisional seperti ikan masak kuning, sambal terasi, dan sayur bening kangkung. Warung sederhana ini sangat populer di kalangan lokal karena rasanya yang otentik dan harganya yang sangat terjangkau. Pengalaman makan di sini memberikan wisatawan wawasan tentang makanan sehari-hari masyarakat Muna Barat.',
                'address' => 'Jl. Pasar Lama No. 7, Kec. Lawa',
                'latitude' => -4.9540,
                'longitude' => 122.5465,
                'district_id' => $districts->where('name', 'Lawa')->first()->id,
                'price_range_start' => 10000,
                'price_range_end' => 25000,
                'opening_hours' => '06:00-15:00',
                'contact_person' => 'Ibu Intan',
                'phone_number' => '081298765432',
                'featured_image' => null,
                'status' => true,
                'social_media' => null,
                'has_vegetarian_option' => true,
                'halal_certified' => false, // warung tradisional biasanya tidak memiliki sertifikasi formal
                'has_delivery' => false,
                'featured_menu' => json_encode([
                    'Kasuami',
                    'Ikan Kuah Kuning',
                    'Sambal Terasi Tomat',
                    'Sayur Kangkung',
                    'Ayam Panggang Muna'
                ]),
                'is_recommended' => true
            ],
            [
                'name' => 'Kedai Es Kelapa Muda Bang Ali',
                'slug' => 'kedai-es-kelapa-muda-bang-ali',
                'type' => 'warung',
                'description' => 'Kedai Es Kelapa Muda Bang Ali terkenal dengan minuman segar dari kelapa muda yang dipetik langsung dari kebun kelapa sekitar. Selain es kelapa original, Bang Ali menawarkan berbagai varian es kelapa kreatif seperti es kelapa durian, es kelapa cincau, dan es kelapa rujak. Kedai ini juga menyediakan berbagai kudapan ringan seperti pisang goreng, ubi goreng, dan kue tradisional. Tempat yang asri dengan tempat duduk outdoor di bawah pohon kelapa menjadikannya tempat favorit untuk bersantai di sore hari.',
                'address' => 'Jl. Kebun Kelapa No. 23, Kec. Kusambi',
                'latitude' => -4.9120,
                'longitude' => 122.5375,
                'district_id' => $districts->where('name', 'Kusambi')->first()->id,
                'price_range_start' => 5000,
                'price_range_end' => 20000,
                'opening_hours' => '10:00-20:00',
                'contact_person' => 'Bang Ali',
                'phone_number' => '085712345678',
                'featured_image' => null,
                'status' => true,
                'social_media' => '@eskelapabangali',
                'has_vegetarian_option' => true,
                'halal_certified' => false,
                'has_delivery' => true,
                'featured_menu' => json_encode([
                    'Es Kelapa Muda Original',
                    'Es Kelapa Durian',
                    'Es Kelapa Cincau',
                    'Pisang Goreng Keju',
                    'Kue Cucur'
                ]),
                'is_recommended' => false
            ],
            [
                'name' => 'Kafe Kopi Lahundape',
                'slug' => 'kafe-kopi-lahundape',
                'type' => 'kafe',
                'description' => 'Kafe Kopi Lahundape adalah kafe modern yang menyajikan kopi lokal dari biji kopi yang ditanam di sekitar Air Terjun Lahundape. Selain berbagai olahan kopi, kafe ini juga menawarkan makanan ringan dan cake homemade. Interior kafe yang cozy dengan sentuhan rustic dan banyak elemen tanaman menjadikannya tempat yang nyaman untuk bekerja atau mengobrol santai. Kafe ini juga kerap mengadakan workshop meracik kopi dan live music di akhir pekan.',
                'address' => 'Jl. Poros Lahundape No. 15, Kec. Barangka',
                'latitude' => -4.9210,
                'longitude' => 122.5631,
                'district_id' => $districts->where('name', 'Barangka')->first()->id,
                'price_range_start' => 15000,
                'price_range_end' => 45000,
                'opening_hours' => '08:00-22:00',
                'contact_person' => 'Bapak Dimas',
                'phone_number' => '081387654321',
                'featured_image' => null,
                'status' => true,
                'social_media' => '@kopilahundape',
                'has_vegetarian_option' => true,
                'halal_certified' => true,
                'has_delivery' => true,
                'featured_menu' => json_encode([
                    'Kopi Lahundape',
                    'Es Kopi Susu Gula Aren',
                    'Roti Bakar Pisang Coklat',
                    'Kentang Goreng Truffle',
                    'Cheesecake Durian'
                ]),
                'is_recommended' => true
            ],
            [
                'name' => 'Pondok Ikan Bakar Rahampu',
                'slug' => 'pondok-ikan-bakar-rahampu',
                'type' => 'warung',
                'description' => 'Pondok Ikan Bakar Rahampu adalah tempat makan seafood yang terletak di dataran tinggi dengan pemandangan spektakuler ke arah laut dan perkebunan kopi. Ikan yang disajikan berasal dari hasil tangkapan nelayan pagi hari dan diolah dengan bumbu tradisional khas pegunungan Muna. Pengalaman makan di sini ditingkatkan dengan udara segar pegunungan dan pemandangan sunset yang memukau. Area makan terdiri dari saung-saung bambu yang tertata rapi di tengah kebun.',
                'address' => 'Desa Rahampu, Kec. Wadaga',
                'latitude' => -4.9285,
                'longitude' => 122.5730,
                'district_id' => $districts->where('name', 'Wadaga')->first()->id,
                'price_range_start' => 35000,
                'price_range_end' => 120000,
                'opening_hours' => '11:00-19:00',
                'contact_person' => 'Pak Hamid',
                'phone_number' => '085890123456',
                'featured_image' => null,
                'status' => true,
                'social_media' => null,
                'has_vegetarian_option' => false,
                'halal_certified' => true,
                'has_delivery' => false,
                'featured_menu' => json_encode([
                    'Ikan Bakar Bumbu Dabu-dabu',
                    'Ikan Kuah Asam Pedas',
                    'Cumi Bakar Kecap',
                    'Udang Goreng Mentega',
                    'Sayur Pakis Tumis'
                ]),
                'is_recommended' => true
            ],
            [
                'name' => 'Kedai Jajanan Pasar Bu Marni',
                'slug' => 'kedai-jajanan-pasar-bu-marni',
                'type' => 'warung',
                'description' => 'Kedai Jajanan Pasar Bu Marni adalah tempat yang tepat untuk mencicipi berbagai kue tradisional dan jajanan pasar khas Muna. Semua makanan dibuat fresh setiap pagi menggunakan bahan-bahan lokal dan resep turun temurun. Kedai ini merupakan bagian dari upaya Bu Marni untuk melestarikan kuliner tradisional Muna agar tidak punah. Pengunjung dapat mencicipi berbagai kue seperti cucur, onde-onde, kue lapis, dan berbagai jajanan yang mungkin sulit ditemukan di tempat lain.',
                'address' => 'Pasar Tradisional Sawerigadi, Kec. Sawerigadi',
                'latitude' => -4.9755,
                'longitude' => 122.4935,
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id,
                'price_range_start' => 2000,
                'price_range_end' => 15000,
                'opening_hours' => '06:00-12:00',
                'contact_person' => 'Ibu Marni',
                'phone_number' => '082156789012',
                'featured_image' => null,
                'status' => true,
                'social_media' => null,
                'has_vegetarian_option' => true,
                'halal_certified' => false,
                'has_delivery' => true,
                'featured_menu' => json_encode([
                    'Kue Cucur',
                    'Onde-onde',
                    'Kue Lapis Muna',
                    'Kue Bangke-bangke',
                    'Sanggara'
                ]),
                'is_recommended' => false
            ],
        ];

        // Simpan data kuliner ke database
        foreach ($culinaries as $culinaryData) {
            $this->command->info("Menambahkan kuliner: {$culinaryData['name']} dengan tipe: {$culinaryData['type']}");

            try {
                $culinary = Culinary::create($culinaryData);

                // Contoh menambahkan gambar dummy untuk setiap kuliner
                // Perhatikan bahwa ini hanya contoh, path gambar perlu disesuaikan
                $imageName = strtolower(str_replace(' ', '_', $culinary->name)) . '.jpg';

                // Tambahkan debug info
                $this->command->info("Menambahkan galeri untuk: {$culinary->name}");

                Gallery::create([
                    'imageable_id' => $culinary->id,
                    'imageable_type' => Culinary::class,
                    'file_path' => 'dummy/culinaries/' . $imageName,
                    'caption' => 'Foto ' . $culinary->name,
                    'is_featured' => true,
                    'order' => 0,
                ]);

                $this->command->info("Berhasil menambahkan kuliner: {$culinary->name}");
            } catch (\Exception $e) {
                $this->command->error("Gagal menambahkan kuliner: {$culinaryData['name']}");
                $this->command->error("Error: " . $e->getMessage());
            }
        }

        $this->command->info('Culinary seeder berhasil dijalankan. ' . count($culinaries) . ' tempat kuliner telah ditambahkan.');
    }
}
