<?php

namespace Database\Seeders;

use App\Models\Transportation;
use App\Models\District;
use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class TransportationSeeder extends Seeder
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
                if (Schema::hasTable('travel_package_transportation')) {
                    DB::table('travel_package_transportation')->delete();
                }

                // Hapus galleries yang terkait dengan transportations
                Gallery::where('imageable_type', 'App\Models\Transportation')->delete();

                // Sekarang aman untuk menghapus transportation
                Transportation::truncate();

                // Aktifkan kembali foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $e) {
                $this->command->error('Error saat menghapus data: ' . $e->getMessage());

                // Jika terjadi error, gunakan cara alternative
                $this->command->info('Menggunakan metode alternatif untuk menghapus data...');

                // Hapus data dari tabel pivot terlebih dahulu
                if (Schema::hasTable('travel_package_transportation')) {
                    DB::table('travel_package_transportation')->delete();
                }

                // Hapus galleries yang terkait dengan transportations
                Gallery::where('imageable_type', 'App\Models\Transportation')->delete();

                // Hapus data transportation satu per satu
                Transportation::query()->delete();
            }
        }

        // Mendapatkan referensi ke kecamatan yang ada
        $districts = District::all();

        if ($districts->isEmpty()) {
            $this->command->error('Tidak ada district yang tersedia. Silahkan jalankan DistrictSeeder terlebih dahulu.');
            return;
        }

        // Array transportasi di Muna Barat
        $transportations = [
            [
                'name' => 'Kapal Feri ASDP Raha-Kendari',
                'type' => 'laut',
                'subtype' => 'kapal_feri',
                'description' => 'Kapal Feri ASDP yang melayani rute Raha-Kendari adalah transportasi laut utama yang menghubungkan Pulau Muna dengan daratan Sulawesi Tenggara. Kapal ini beroperasi setiap hari dengan jadwal keberangkatan pagi dan sore hari.

Kapal feri ini dapat mengangkut penumpang maupun kendaraan (mobil, motor, dan truk), menjadikannya pilihan yang ideal bagi wisatawan yang ingin membawa kendaraan pribadi. Perjalanan dari Raha ke Kendari memakan waktu sekitar 2-3 jam tergantung kondisi laut.

Fasilitas di kapal termasuk kursi penumpang yang nyaman, kafetaria sederhana yang menyediakan makanan dan minuman, area dek terbuka untuk menikmati pemandangan laut, serta toilet umum. Tiket dapat dibeli langsung di loket pelabuhan atau dipesan sehari sebelumnya untuk memastikan ketersediaan tempat.

Pelayanan kapal feri ini dioperasikan oleh PT ASDP Indonesia Ferry (Persero), perusahaan pelayaran milik negara yang memiliki standar keselamatan dan keamanan yang baik. Kapal dilengkapi dengan alat keselamatan seperti sekoci, pelampung, dan alat pemadam kebakaran sesuai standar.',
                'capacity' => 250,
                'price_scheme' => 'fixed',
                'base_price' => 50000,
                'contact_person' => 'Kantor ASDP Raha',
                'phone_number' => '04011234567',
                'email' => 'adsp.raha@gmail.com',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'routes' => [
                    [
                        'origin' => 'Pelabuhan Raha',
                        'destination' => 'Pelabuhan Kendari',
                        'distance' => 45,
                        'duration' => 150,
                        'schedules' => [
                            ['day' => 'daily', 'departure_time' => '07:00', 'arrival_time' => '09:30'],
                            ['day' => 'daily', 'departure_time' => '15:00', 'arrival_time' => '17:30'],
                        ],
                        'price' => 50000,
                    ],
                    [
                        'origin' => 'Pelabuhan Kendari',
                        'destination' => 'Pelabuhan Raha',
                        'distance' => 45,
                        'duration' => 150,
                        'schedules' => [
                            ['day' => 'daily', 'departure_time' => '10:30', 'arrival_time' => '13:00'],
                            ['day' => 'daily', 'departure_time' => '18:30', 'arrival_time' => '21:00'],
                        ],
                        'price' => 50000,
                    ],
                ],
                'status' => true,
            ],
            [
                'name' => 'Speedboat Express Tiworo',
                'type' => 'laut',
                'subtype' => 'speedboat',
                'description' => 'Speedboat Express Tiworo adalah layanan kapal cepat yang menghubungkan berbagai pulau dan pantai di sekitar Kepulauan Tiworo, Muna Barat. Dengan menggunakan kapal bermesin ganda yang modern, speedboat ini menawarkan perjalanan yang jauh lebih cepat dibandingkan kapal biasa.

Speedboat ini cocok untuk wisatawan yang ingin mengunjungi pulau-pulau terpencil atau pantai yang tidak terjangkau melalui jalur darat. Perjalanan dengan Speedboat Express Tiworo juga memberikan pengalaman tersendiri dengan pemandangan laut dan pulau-pulau kecil yang indah sepanjang perjalanan.

Setiap speedboat dilengkapi dengan jaket pelampung untuk semua penumpang, terpal pelindung dari sinar matahari dan hujan, serta kotak P3K untuk keadaan darurat. Kapal dioperasikan oleh nahkoda berpengalaman yang sangat mengenal perairan sekitar Kepulauan Tiworo.

Layanan ini bisa dipesan untuk trip reguler ke tujuan populer atau disewa secara khusus (charter) untuk mengunjungi lokasi sesuai keinginan penumpang. Untuk pemesanan charter, sebaiknya dilakukan minimal sehari sebelumnya untuk persiapan BBM dan perbekalan.',
                'capacity' => 15,
                'price_scheme' => 'fixed',
                'base_price' => 100000,
                'contact_person' => 'Pak Ramli',
                'phone_number' => '082345678901',
                'email' => 'tiworoexpress@gmail.com',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? 2,
                'routes' => [
                    [
                        'origin' => 'Dermaga Tiworo',
                        'destination' => 'Pantai Napabale',
                        'distance' => 15,
                        'duration' => 30,
                        'schedules' => [
                            ['day' => 'daily', 'departure_time' => '09:00', 'arrival_time' => '09:30'],
                            ['day' => 'daily', 'departure_time' => '14:00', 'arrival_time' => '14:30'],
                        ],
                        'price' => 100000,
                    ],
                    [
                        'origin' => 'Dermaga Tiworo',
                        'destination' => 'Pulau Muna Kecil',
                        'distance' => 25,
                        'duration' => 45,
                        'schedules' => [
                            ['day' => 'monday', 'departure_time' => '10:00', 'arrival_time' => '10:45'],
                            ['day' => 'thursday', 'departure_time' => '10:00', 'arrival_time' => '10:45'],
                            ['day' => 'saturday', 'departure_time' => '10:00', 'arrival_time' => '10:45'],
                        ],
                        'price' => 150000,
                    ],
                ],
                'status' => true,
            ],
            [
                'name' => 'Rental Mobil Muna Explore',
                'type' => 'darat',
                'subtype' => 'mobil_rental',
                'description' => 'Rental Mobil Muna Explore menyediakan jasa rental kendaraan untuk menjelajahi Kabupaten Muna Barat dan sekitarnya. Dengan armada yang terawat dan bervariasi, Muna Explore memberikan kebebasan bagi wisatawan untuk menjelajahi destinasi wisata sesuai jadwal mereka sendiri.

Armada kendaraan terdiri dari mobil-mobil nyaman seperti Avanza, Xenia, Innova, dan mobil jenis SUV yang cocok untuk jalanan berbagai kondisi di Muna Barat. Semua kendaraan dilengkapi dengan AC yang berfungsi dengan baik, sistem audio, dan dilakukan perawatan rutin untuk memastikan keamanan dan kenyamanan.

Rental Mobil Muna Explore menawarkan beberapa opsi sewa: lepas kunci (tanpa supir) bagi yang sudah familiar dengan jalanan Muna, atau dengan supir berpengalaman yang juga bisa menjadi pemandu wisata lokal. Paket sewa tersedia dalam hitungan jam, harian, hingga mingguan dengan harga yang bersaing.

Proses penyewaan sangat mudah, cukup dengan menunjukkan KTP dan SIM yang masih berlaku (untuk opsi lepas kunci), serta deposit yang akan dikembalikan saat pengembalian kendaraan. Kantor pusat Muna Explore berlokasi strategis di dekat pusat kota dan juga menawarkan layanan antar-jemput kendaraan ke lokasi pelanggan.',
                'capacity' => 6,
                'price_scheme' => 'hourly',
                'base_price' => 50000,
                'contact_person' => 'Bapak Andi',
                'phone_number' => '081234567890',
                'email' => 'munaexplore@gmail.com',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'routes' => null,
                'status' => true,
            ],
            [
                'name' => 'Ojek Wisata Muna',
                'type' => 'darat',
                'subtype' => 'ojek',
                'description' => 'Ojek Wisata Muna adalah layanan transportasi sepeda motor yang dikhususkan untuk memenuhi kebutuhan wisatawan di Muna Barat. Layanan ini menawarkan cara praktis dan ekonomis untuk menjelajahi destinasi wisata, terutama lokasi-lokasi yang sulit dijangkau dengan kendaraan besar.

Dioperasikan oleh penduduk lokal yang sangat mengenal wilayah sekitar, Ojek Wisata Muna tidak hanya menawarkan jasa transportasi tetapi juga pengetahuan lokal tentang tempat wisata, kuliner, dan budaya setempat. Para pengojek telah dilatih untuk memberikan pelayanan yang ramah dan informatif kepada wisatawan.

Ojek Wisata Muna memiliki pangkalan di beberapa titik strategis seperti pelabuhan, terminal, dan dekat objek wisata populer. Mereka juga bisa dipesan melalui telepon atau aplikasi pesan untuk penjemputan di lokasi spesifik. Tarif ditentukan berdasarkan jarak tempuh dengan harga yang sudah disepakati sebelum perjalanan dimulai.

Untuk kenyamanan dan keamanan, Ojek Wisata Muna menyediakan helm untuk penumpang dan jas hujan jika diperlukan. Mereka juga menawarkan paket wisata harian dimana penumpang bisa menggunakan jasa ojek untuk berkeliling ke beberapa destinasi dalam satu hari dengan tarif yang lebih ekonomis.',
                'capacity' => 1,
                'price_scheme' => 'distance',
                'base_price' => 5000,
                'contact_person' => 'Koordinator Ojek',
                'phone_number' => '087654321098',
                'email' => null,
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'routes' => [
                    [
                        'origin' => 'Pelabuhan Raha',
                        'destination' => 'Pusat Kota',
                        'distance' => 3,
                        'duration' => 15,
                        'price' => 15000,
                    ],
                    [
                        'origin' => 'Terminal Lawa',
                        'destination' => 'Pantai Napabale',
                        'distance' => 8,
                        'duration' => 30,
                        'price' => 40000,
                    ],
                ],
                'status' => true,
            ],
            [
                'name' => 'Bus Trans Muna',
                'type' => 'darat',
                'subtype' => 'bus',
                'description' => 'Bus Trans Muna adalah layanan transportasi umum yang menghubungkan berbagai kecamatan di Kabupaten Muna dan Muna Barat. Dengan armada bus berukuran sedang yang nyaman, Trans Muna memudahkan pergerakan penduduk lokal maupun wisatawan antar wilayah dengan biaya terjangkau.

Armada Bus Trans Muna terdiri dari kendaraan berkapasitas 30 penumpang yang dilengkapi dengan AC, tempat duduk ergonomis, dan ruang bagasi untuk barang bawaan. Bus-bus ini dioperasikan dengan jadwal tetap dan rute yang melewati pusat-pusat keramaian, terminal, serta dekat dengan objek-objek wisata populer di Muna Barat.

Tiket Bus Trans Muna dijual dengan harga terjangkau dan dapat dibeli langsung di dalam bus atau di loket-loket resmi. Tersedia juga tiket langganan untuk penumpang rutin dengan harga lebih ekonomis. Anak-anak di bawah 5 tahun dan lansia di atas 60 tahun mendapatkan diskon khusus.

Bus Trans Muna menjadi pilihan transportasi yang ramah lingkungan dan ekonomis untuk menjelajahi Kabupaten Muna Barat, terutama bagi wisatawan dengan budget terbatas atau yang ingin mendapatkan pengalaman bepergian bersama penduduk lokal untuk lebih mengenal kehidupan sehari-hari masyarakat.',
                'capacity' => 30,
                'price_scheme' => 'fixed',
                'base_price' => 10000,
                'contact_person' => 'Terminal Bus Muna',
                'phone_number' => '04011234567',
                'email' => 'transmuna@munakab.go.id',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'routes' => [
                    [
                        'origin' => 'Terminal Lawa',
                        'destination' => 'Terminal Sawerigadi',
                        'distance' => 15,
                        'duration' => 45,
                        'schedules' => [
                            ['day' => 'daily', 'departure_time' => '08:00', 'arrival_time' => '08:45'],
                            ['day' => 'daily', 'departure_time' => '12:00', 'arrival_time' => '12:45'],
                            ['day' => 'daily', 'departure_time' => '16:00', 'arrival_time' => '16:45'],
                        ],
                        'price' => 10000,
                    ],
                    [
                        'origin' => 'Terminal Sawerigadi',
                        'destination' => 'Terminal Lawa',
                        'distance' => 15,
                        'duration' => 45,
                        'schedules' => [
                            ['day' => 'daily', 'departure_time' => '10:00', 'arrival_time' => '10:45'],
                            ['day' => 'daily', 'departure_time' => '14:00', 'arrival_time' => '14:45'],
                            ['day' => 'daily', 'departure_time' => '18:00', 'arrival_time' => '18:45'],
                        ],
                        'price' => 10000,
                    ],
                ],
                'status' => true,
            ],
        ];

        foreach ($transportations as $transportation) {
            try {
                $slug = Str::slug($transportation['name']);
                $transportation['slug'] = $slug;

                // Verifikasi bahwa semua kolom dalam data sesuai dengan kolom di tabel
                $availableColumns = Schema::getColumnListing('transportations');
                $transportationData = array_intersect_key($transportation, array_flip($availableColumns));

                $newTransportation = Transportation::create($transportationData);

                // Buat galeri untuk transportasi
                $galleryCount = rand(2, 4); // 2-4 gambar per transportasi
                for ($i = 1; $i <= $galleryCount; $i++) {
                    Gallery::create([
                        'imageable_id' => $newTransportation->id,
                        'imageable_type' => get_class($newTransportation),
                        'file_path' => 'dummy/transportations/' . $slug . '_' . $i . '.jpg',
                        'caption' => $transportation['name'] . ' - ' . ($i === 1 ? 'Exterior' : ($i === 2 ? 'Interior' : ($i === 3 ? 'In Service' : 'View'))),
                        'is_featured' => ($i === 1), // Gambar pertama adalah featured
                        'order' => $i,
                    ]);
                }

                // Set featured image dari galeri pertama
                $newTransportation->update([
                    'featured_image' => 'dummy/transportations/' . $slug . '_1.jpg'
                ]);

                $this->command->info("Transportasi {$transportation['name']} berhasil dibuat.");
            } catch (\Exception $e) {
                $this->command->error("Error saat membuat transportasi {$transportation['name']}: " . $e->getMessage());
            }
        }

        $this->command->info(count($transportations) . ' transportasi berhasil ditambahkan!');
    }
}
