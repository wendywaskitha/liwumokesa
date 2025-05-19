<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\District;
use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data dengan cara yang tidak melanggar foreign key constraints
        if (app()->environment() !== 'production') {
            try {
                // Nonaktifkan foreign key checks sementara
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Hapus data dari tabel pivot terlebih dahulu jika ada
                if (Schema::hasTable('destination_travel_package')) {
                    DB::table('destination_travel_package')->delete();
                }

                if (Schema::hasTable('destination_tour_guide')) {
                    DB::table('destination_tour_guide')->delete();
                }

                // Hapus galleries yang terkait dengan destinations
                Gallery::where('imageable_type', 'App\Models\Destination')->delete();

                // Sekarang aman untuk menghapus destinations
                Destination::truncate();

                // Aktifkan kembali foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $e) {
                $this->command->error('Error saat menghapus data: ' . $e->getMessage());

                // Jika terjadi error, gunakan cara alternative
                $this->command->info('Menggunakan metode alternatif untuk menghapus data...');

                // Hapus data dari tabel pivot terlebih dahulu
                if (Schema::hasTable('destination_travel_package')) {
                    DB::table('destination_travel_package')->delete();
                }

                if (Schema::hasTable('destination_tour_guide')) {
                    DB::table('destination_tour_guide')->delete();
                }

                // Hapus galleries yang terkait dengan destinations
                Gallery::where('imageable_type', 'App\Models\Destination')->delete();

                // Hapus data destinations satu per satu
                Destination::query()->delete();
            }
        }

        // Mendapatkan referensi ke kecamatan dan kategori yang ada
        $districts = District::all();

        // Cek keberadaan kategori
        $hasCategoryRelation = Schema::hasTable('categories') &&
                              Schema::hasColumn('destinations', 'category_id');

        $categories = $hasCategoryRelation ? Category::where('type', 'destination')->get() : collect([]);

        if ($districts->isEmpty()) {
            $this->command->error('Tidak ada district yang tersedia. Silahkan jalankan DistrictSeeder terlebih dahulu.');
            return;
        }

        // Array destinasi wisata baru di Muna Barat
        $destinations = [
            // 1. Pulau Mandike - Pulau kecil yang indah
            [
                'name' => 'Pulau Mandike',
                'description' => 'Pulau Mandike adalah surga tersembunyi di Kepulauan Tiworo, Muna Barat. Dikelilingi oleh lautan biru jernih dengan gradasi warna yang memukau, pulau ini menawarkan pantai pasir putih yang lembut dan pemandangan bawah laut yang kaya biodiversitas.

Terumbu karang di sekitar pulau dihuni oleh beragam spesies ikan karang berwarna-warni, menjadikan area ini spot snorkeling dan diving yang populer. Vegetasi pantai yang rindang menyediakan tempat berteduh yang nyaman untuk pengunjung.

Pulau ini relatif belum terjamah dan mempertahankan keindahan alamnya yang alami. Untuk mencapai Pulau Mandike, pengunjung harus menggunakan perahu dari dermaga Tiworo dengan waktu tempuh sekitar 30-45 menit.',
                'type' => 'beach',
                'location' => 'Kepulauan Tiworo, Kecamatan Tiworo Kepulauan',
                'district_id' => $districts->where('name', 'Tiworo Kepulauan')->first()->id ?? 1,
                'latitude' => -4.8512,
                'longitude' => 122.4215,
                'visiting_hours' => '06:00-18:00',
                'entrance_fee' => 25000,
                'facilities' => [
                    [
                        'name' => 'Penyewaan perahu',
                        'description' => 'Tersedia untuk mengelilingi pulau',
                        'type' => 'basic',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Penyewaan alat snorkeling',
                        'description' => 'Tersedia dalam jumlah terbatas',
                        'type' => 'comfort',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Warung makan',
                        'description' => 'Menyediakan hidangan seafood segar',
                        'type' => 'basic',
                        'is_available' => true
                    ]
                ],
                'website' => 'https://munabaratkab.go.id/wisata/pulau-mandike',
                'contact' => '082187654321',
                'best_time_to_visit' => 'Pagi hingga sore hari, hindari musim hujan',
                'tips' => 'Bawalah perlengkapan snorkeling sendiri, sunscreen, dan air minum yang cukup. Perhatikan jadwal perahu terakhir untuk kembali ke daratan utama.',
                'is_featured' => true,
                'status' => true,
                'category_id' => $hasCategoryRelation ?
                    ($categories->where('name', 'Pantai')->first()->id ?? null) : null,
            ],

            // 2. Gua Metanduno - Gua prasejarah
            [
                'name' => 'Gua Metanduno',
                'description' => 'Gua Metanduno merupakan salah satu situs arkeologi penting di Muna Barat. Dengan usia diperkirakan mencapai 6.000-8.000 tahun, gua ini menyimpan lukisan prasejarah berupa gambar telapak tangan, binatang buruan, adegan berburu, dan simbol-simbol geometris yang dilukis dengan pigmen alami.

Gua ini terdiri dari beberapa ruangan dan lorong dengan langit-langit setinggi 4-6 meter. Lukisan-lukisan prasejarah tersebar di beberapa bagian dinding gua, dengan kondisi yang masih cukup jelas terlihat. Situs ini menjadi bukti penting keberadaan peradaban kuno di Sulawesi Tenggara.

Para pengunjung dapat menjelajahi gua dengan didampingi pemandu lokal yang akan menjelaskan makna dan signifikansi sejarah di balik lukisan-lukisan tersebut. Selain nilai arkeologisnya, formasi batu kapur di dalam gua juga menawarkan pemandangan yang menakjubkan.',
                'type' => 'historical',
                'location' => 'Desa Liangkobori, Kecamatan Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 2,
                'latitude' => -4.9185,
                'longitude' => 122.5614,
                'visiting_hours' => '08:00-17:00',
                'entrance_fee' => 15000,
                'facilities' => [
                    [
                        'name' => 'Area parkir',
                        'description' => 'Tersedia untuk kendaraan pengunjung',
                        'type' => 'basic',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Pemandu lokal',
                        'description' => 'Dapat disewa untuk menjelaskan sejarah gua',
                        'type' => 'service',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Toilet umum',
                        'description' => 'Tersedia di area pintu masuk',
                        'type' => 'basic',
                        'is_available' => true
                    ]
                ],
                'website' => null,
                'contact' => '085789123456',
                'best_time_to_visit' => 'Pagi hingga siang hari, ketika cahaya matahari cukup',
                'tips' => 'Gunakan sepatu yang nyaman untuk menjelajahi gua, bawa senter kecil sebagai cadangan, dan patuhi petunjuk pemandu untuk menjaga kelestarian lukisan gua.',
                'is_featured' => true,
                'status' => true,
                'category_id' => $hasCategoryRelation ?
                    ($categories->where('name', 'Sejarah')->first()->id ?? null) : null,
            ],

            // 3. Benteng La Kalimporo - Benteng bersejarah
            [
                'name' => 'Benteng La Kalimporo',
                'description' => 'Benteng La Kalimporo adalah benteng bersejarah yang dibangun pada masa Kesultanan Muna sekitar abad ke-16. Terletak di atas bukit dengan pemandangan strategis ke arah laut, benteng ini dibangun sebagai pertahanan dari serangan musuh dan bajak laut.

Konstruksi benteng terbuat dari susunan batu karang yang disusun tanpa perekat, menunjukkan keahlian arsitektur tradisional yang tinggi. Dinding benteng memiliki ketebalan sekitar 1,5-2 meter dengan tinggi mencapai 3 meter di beberapa bagian. Benteng ini memiliki beberapa pos pengintai dan gerbang utama yang masih dapat dilihat hingga kini.

Benteng La Kalimporo memiliki nilai sejarah yang tinggi sebagai simbol perjuangan masyarakat Muna melawan penjajahan dan serangan bajak laut. Dari area benteng, pengunjung dapat menikmati pemandangan panorama laut dan sebagian wilayah Muna Barat.',
                'type' => 'historical',
                'location' => 'Desa Wasolangka, Kecamatan Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 2,
                'latitude' => -4.9217,
                'longitude' => 122.5522,
                'visiting_hours' => '08:00-18:00',
                'entrance_fee' => 10000,
                'facilities' => [
                    [
                        'name' => 'Area parkir',
                        'description' => 'Tersedia untuk kendaraan pengunjung',
                        'type' => 'basic',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Papan informasi',
                        'description' => 'Menjelaskan sejarah benteng',
                        'type' => 'information',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Gazebo',
                        'description' => 'Tempat istirahat untuk pengunjung',
                        'type' => 'comfort',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Toilet umum',
                        'description' => 'Tersedia di area pintu masuk',
                        'type' => 'basic',
                        'is_available' => true
                    ]
                ],
                'website' => 'https://munabaratkab.go.id/wisata/benteng-la-kalimporo',
                'contact' => '081234567890',
                'best_time_to_visit' => 'Pagi atau sore hari untuk menghindari panas terik',
                'tips' => 'Kenakan pakaian yang nyaman dan bawa air minum yang cukup. Sepatu yang sesuai sangat direkomendasikan karena medan berbatu.',
                'is_featured' => false,
                'status' => true,
                'category_id' => $hasCategoryRelation ?
                    ($categories->where('name', 'Sejarah')->first()->id ?? null) : null,
            ],

            // 4. Air Terjun Wakuli - Air terjun bertingkat
            [
                'name' => 'Air Terjun Wakuli',
                'description' => 'Air Terjun Wakuli adalah salah satu keindahan alam tersembunyi di Muna Barat. Terletak di tengah hutan tropis yang rimbun, air terjun ini memiliki tiga tingkatan dengan ketinggian total sekitar 20 meter. Airnya yang jernih berasal dari mata air pegunungan yang mengalir sepanjang tahun.

Di bawah air terjun terdapat kolam alami yang cukup dalam dan luas, ideal untuk berenang dan bermain air. Suasana di sekitar air terjun sangat sejuk dan tenang, dengan suara gemericik air yang menenangkan dan pepohonan rindang yang memberikan keteduhan alami.

Trek menuju air terjun melalui hutan selama sekitar 30 menit, menawarkan pengalaman singkat trekking dengan pemandangan flora khas Sulawesi. Sepanjang jalan, pengunjung dapat melihat berbagai jenis burung dan kupu-kupu yang hidup di ekosistem hutan tersebut.',
                'type' => 'nature',
                'location' => 'Desa Wakuli, Kecamatan Kabawo',
                'district_id' => $districts->where('name', 'Kabawo')->first()->id ?? 3,
                'latitude' => -4.9071,
                'longitude' => 122.6014,
                'visiting_hours' => '07:00-17:00',
                'entrance_fee' => 15000,
                'facilities' => [
                    [
                        'name' => 'Area parkir',
                        'description' => 'Tersedia di titik awal trek',
                        'type' => 'basic',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Gazebo',
                        'description' => 'Tempat istirahat di sekitar air terjun',
                        'type' => 'comfort',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Warung makanan',
                        'description' => 'Menjual makanan ringan dan minuman',
                        'type' => 'basic',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Kamar ganti',
                        'description' => 'Untuk berganti pakaian setelah berenang',
                        'type' => 'basic',
                        'is_available' => true
                    ]
                ],
                'website' => null,
                'contact' => '082345678912',
                'best_time_to_visit' => 'Pagi hingga siang hari saat cuaca cerah',
                'tips' => 'Bawa pakaian ganti jika ingin berenang, alas kaki anti selip untuk trekking, dan perlengkapan P3K sederhana. Berhati-hatilah saat musim hujan karena jalur trek dapat licin.',
                'is_featured' => true,
                'status' => true,
                'category_id' => $hasCategoryRelation ?
                    ($categories->where('name', 'Alam')->first()->id ?? null) : null,
            ],

            // 5. Desa Tenun Lontiala - Desa wisata budaya
            [
                'name' => 'Desa Tenun Lontiala',
                'description' => 'Desa Tenun Lontiala adalah pusat kerajinan tenun tradisional di Muna Barat. Desa ini telah melestarikan teknik tenun tradisional yang diwariskan turun temurun selama berabad-abad. Para pengrajin, sebagian besar wanita, menghasilkan kain tenun dengan motif khas Muna yang kaya makna filosofis dan budaya.

Pengunjung dapat menyaksikan langsung proses pembuatan kain tenun mulai dari pemintalan benang, pewarnaan dengan bahan alami, hingga proses menenun di alat tenun tradisional. Workshop interaktif juga tersedia bagi wisatawan yang ingin belajar dasar-dasar menenun langsung dari para pengrajin lokal.

Desa ini juga memiliki galeri dan toko yang menjual berbagai produk tenun mulai dari kain, sarung, hingga aksesoris dan perlengkapan rumah tangga berbahan tenun. Setiap produk memiliki keunikan tersendiri dengan motif dan warna yang berbeda-beda sesuai dengan makna dan fungsinya dalam budaya Muna.',
                'type' => 'cultural',
                'location' => 'Desa Lontiala, Kecamatan Tiworo Selatan',
                'district_id' => $districts->where('name', 'Tiworo Selatan')->first()->id ?? 4,
                'latitude' => -4.9312,
                'longitude' => 122.5118,
                'visiting_hours' => '09:00-16:00',
                'entrance_fee' => 10000,
                'facilities' => [
                    [
                        'name' => 'Area parkir',
                        'description' => 'Tersedia untuk kendaraan pengunjung',
                        'type' => 'basic',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Showroom tenun',
                        'description' => 'Tempat memajang dan menjual produk tenun',
                        'type' => 'basic',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Workshop interaktif',
                        'description' => 'Area belajar menenun untuk pengunjung',
                        'type' => 'service',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Homestay',
                        'description' => 'Tersedia untuk menginap di rumah penduduk',
                        'type' => 'comfort',
                        'is_available' => true
                    ],
                    [
                        'name' => 'Warung makan',
                        'description' => 'Menyediakan makanan khas Muna',
                        'type' => 'basic',
                        'is_available' => true
                    ]
                ],
                'website' => 'https://desatenunontiala.com',
                'contact' => '085678901234',
                'best_time_to_visit' => 'Pagi hingga sore hari, terutama pada hari kerja',
                'tips' => 'Hubungi terlebih dahulu untuk reservasi workshop tenun. Sediakan waktu setidaknya 2-3 jam untuk mengeksplorasi dan berinteraksi dengan pengrajin. Pastikan membawa uang tunai untuk membeli produk tenun karena pembayaran digital mungkin terbatas.',
                'is_featured' => true,
                'status' => true,
                'category_id' => $hasCategoryRelation ?
                    ($categories->where('name', 'Budaya')->first()->id ?? null) : null,
            ],
        ];

        foreach ($destinations as $destination) {
            try {
                $slug = Str::slug($destination['name']);
                $destination['slug'] = $slug;

                // Simpan facilities terpisah untuk di-encode menjadi JSON
                $facilities = $destination['facilities'] ?? [];
                $destination['facilities'] = json_encode($facilities);

                $newDestination = Destination::create($destination);

                // Buat galeri untuk destinasi
                $galleryCount = rand(2, 5); // 2-5 gambar per destinasi
                for ($i = 1; $i <= $galleryCount; $i++) {
                    Gallery::create([
                        'imageable_id' => $newDestination->id,
                        'imageable_type' => get_class($newDestination),
                        'file_path' => 'dummy/destinations/' . $slug . '_' . $i . '.jpg',
                        'caption' => $destination['name'] . ' view ' . $i,
                        'is_featured' => ($i === 1), // Gambar pertama adalah featured
                        'order' => $i,
                    ]);
                }

                // Set featured image dari galeri pertama
                $newDestination->update([
                    'featured_image' => 'dummy/destinations/' . $slug . '_1.jpg'
                ]);

                $this->command->info("Destinasi '{$destination['name']}' berhasil ditambahkan.");
            } catch (\Exception $e) {
                $this->command->error("Error saat menambahkan destinasi '{$destination['name']}': " . $e->getMessage());
            }
        }

        $this->command->info('Total ' . count($destinations) . ' destinasi berhasil ditambahkan!');
    }
}
