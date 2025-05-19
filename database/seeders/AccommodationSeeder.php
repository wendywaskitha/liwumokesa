<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\District;
use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AccommodationSeeder extends Seeder
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
                if (Schema::hasTable('accommodation_travel_package')) {
                    DB::table('accommodation_travel_package')->delete();
                }

                // Hapus galleries yang terkait dengan accommodations
                Gallery::where('imageable_type', 'App\Models\Accommodation')->delete();

                // Sekarang aman untuk menghapus accommodations
                Accommodation::truncate();

                // Aktifkan kembali foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $e) {
                $this->command->error('Error saat menghapus data: ' . $e->getMessage());

                // Jika terjadi error, gunakan cara alternative
                $this->command->info('Menggunakan metode alternatif untuk menghapus data...');

                // Hapus data dari tabel pivot terlebih dahulu
                if (Schema::hasTable('accommodation_travel_package')) {
                    DB::table('accommodation_travel_package')->delete();
                }

                // Hapus galleries yang terkait dengan accommodations
                Gallery::where('imageable_type', 'App\Models\Accommodation')->delete();

                // Hapus data accommodations satu per satu
                Accommodation::query()->delete();
            }
        }

        // Mendapatkan referensi ke kecamatan yang ada
        $districts = District::all();

        if ($districts->isEmpty()) {
            $this->command->error('Tidak ada district yang tersedia. Silahkan jalankan DistrictSeeder terlebih dahulu.');
            return;
        }

        // Array akomodasi di Muna Barat yang disesuaikan dengan struktur tabel
        $accommodations = [
            [
                'name' => 'Napabale Beach Resort',
                'description' => 'Napabale Beach Resort adalah akomodasi tepi pantai yang menawarkan pengalaman menginap dengan pemandangan laut langsung dari kamar. Resort ini terdiri dari beberapa cottage dan kamar standar yang didesain dengan sentuhan arsitektur lokal namun tetap memberikan kenyamanan modern.

Setiap cottage memiliki teras pribadi menghadap ke laut dengan hammock dan kursi santai. Interior kamar didesain dengan tema bahari yang menyegarkan, dilengkapi dengan AC, kamar mandi dalam dengan air panas, dan wifi gratis. Resort ini memiliki restoran yang menyajikan hidangan lokal dengan bahan-bahan segar dari laut dan kebun setempat.

Fasilitas resort mencakup kolam renang menghadap laut, area berjemur, layanan pijat tradisional, persewaan peralatan snorkeling, dan layanan antar-jemput ke bandara/pelabuhan. Resort ini juga sering mengadakan acara api unggun di malam hari dan demonstrasi masak kuliner lokal untuk tamu.

Lokasi resort yang tepat di Pantai Napabale memudahkan tamu untuk menikmati aktivitas pantai seperti berenang, snorkeling, atau sekadar berjalan-jalan di tepi pantai menikmati sunset yang memukau.',
                'type' => 'hotel',
                'address' => 'Pantai Napabale, Desa Napabale, Kecamatan Tiworo Utara',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? 1,
                'latitude' => -4.949257,
                'longitude' => 122.536881,
                'price_range_start' => 500000,
                'price_range_end' => 1500000,
                'facilities' => json_encode([
                    'Kolam renang',
                    'Restoran',
                    'WiFi gratis',
                    'AC',
                    'Kamar mandi dalam',
                    'Teras pribadi',
                    'Layanan pijat',
                    'Persewaan peralatan snorkeling',
                    'Area parkir',
                    'Resepsionis 24 jam'
                ]),
                'contact_person' => 'Pengelola Resort Napabale',
                'phone_number' => '082345678901',
                'email' => 'info@napabaleresort.com',
                'website' => 'napabaleresort.com',
                'booking_link' => 'napabaleresort.com/booking',
                'status' => true,
            ],
            [
                'name' => 'Katipalalla Homestay',
                'description' => 'Katipalalla Homestay menawarkan pengalaman menginap autentik di rumah panggung tradisional Muna yang telah direnovasi dengan tetap mempertahankan arsitektur dan nuansa aslinya. Homestay ini dikelola langsung oleh penduduk lokal, memberikan kesempatan kepada tamu untuk berinteraksi dan belajar tentang budaya dan kehidupan sehari-hari masyarakat Muna.

Akomodasi terdiri dari beberapa kamar dalam rumah panggung dengan kasur tradisional yang nyaman, lengkap dengan kelambu dan kipas angin. Kamar mandi bersama tersedia dengan air bersih namun masih menggunakan sistem tradisional. Makanan disajikan bersama dengan keluarga pemilik, menawarkan masakan rumahan khas Muna yang autentik.

Keunikan homestay ini adalah kesempatan untuk terlibat langsung dalam aktivitas sehari-hari masyarakat seperti menenun kain tradisional, membuat kerajinan dari bambu, atau ikut serta dalam upacara adat jika bertepatan dengan kedatangan tamu. Tuan rumah juga bisa menjadi pemandu untuk menjelajahi desa dan area sekitarnya.

Meskipun fasilitas tidak semewah hotel modern, Katipalalla Homestay menawarkan pengalaman budaya yang mendalam dan personal. Lokasi homestay berada di tengah perkampungan tradisional, dikelilingi oleh rumah-rumah panggung lainnya dan area pertanian tradisional.',
                'type' => 'homestay',
                'address' => 'Desa Katipalalla, Kecamatan Sawerigadi',
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id ?? 2,
                'latitude' => -4.952451,
                'longitude' => 122.512146,
                'price_range_start' => 150000,
                'price_range_end' => 300000,
                'facilities' => json_encode([
                    'Sarapan tradisional',
                    'Pengalaman budaya lokal',
                    'WiFi di area umum',
                    'Air minum',
                    'Area parkir'
                ]),
                'contact_person' => 'Wa Ode Sarina',
                'phone_number' => '085678901234',
                'email' => 'katipalallahomestay@gmail.com',
                'website' => null,
                'booking_link' => null,
                'status' => true,
            ],
            [
                'name' => 'Hotel Kabupaten Muna Barat',
                'description' => 'Hotel Kabupaten Muna Barat adalah akomodasi standar yang terletak strategis di pusat administrasi Kabupaten Muna Barat. Hotel ini merupakan pilihan yang ideal bagi wisatawan bisnis dan pejabat pemerintah yang berkunjung ke Muna Barat untuk urusan resmi, namun juga cocok untuk wisatawan umum yang mencari akomodasi nyaman dengan lokasi strategis.

Hotel ini menawarkan kamar-kamar dengan standar modern dan fungsional, dilengkapi dengan AC, TV layar datar, kamar mandi dalam, dan area kerja kecil. Terdapat beberapa tipe kamar mulai dari standar hingga suite yang dapat dipilih sesuai kebutuhan tamu.

Fasilitas hotel mencakup restoran yang menyajikan menu lokal dan internasional, ruang pertemuan yang dapat menampung hingga 100 orang, layanan laundry, dan area parkir yang luas. Koneksi wifi tersedia di seluruh area hotel dan resepsionis beroperasi 24 jam untuk membantu kebutuhan tamu.

Lokasi hotel yang berada di pusat kabupaten memudahkan akses ke kantor pemerintahan, pusat bisnis, dan pasar tradisional. Beberapa destinasi wisata populer juga dapat dijangkau dengan mudah dari hotel ini, menjadikannya titik awal yang baik untuk mengeksplorasi Muna Barat.',
                'type' => 'hotel',
                'address' => 'Jl. Poros Kabupaten No. 10, Kecamatan Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 3,
                'latitude' => -4.939611,
                'longitude' => 122.548863,
                'price_range_start' => 350000,
                'price_range_end' => 800000,
                'facilities' => json_encode([
                    'Restoran',
                    'Ruang pertemuan',
                    'AC',
                    'TV',
                    'WiFi',
                    'Laundry',
                    'Parkir luas',
                    'Resepsionis 24 jam'
                ]),
                'contact_person' => 'Reservasi Hotel',
                'phone_number' => '081234567890',
                'email' => 'booking@hotelmunabaratkab.co.id',
                'website' => 'hotelmunabaratkab.co.id',
                'booking_link' => 'hotelmunabaratkab.co.id/booking',
                'status' => true,
            ],
            [
                'name' => 'Santiri Bay Cottages',
                'description' => 'Santiri Bay Cottages adalah kumpulan cottage sederhana namun nyaman yang terletak di teluk kecil dekat Pantai Santiri. Akomodasi ini merupakan pilihan ideal bagi wisatawan yang mencari tempat menginap tenang dengan akses langsung ke pantai berpasir putih dan perairan jernih.

Setiap cottage dibangun dari bahan-bahan lokal seperti kayu dan atap alang-alang, memberikan nuansa tradisional yang menyatu dengan alam sekitar. Tamu dapat memilih antara cottage beratap rumbia tradisional atau cottage modern dengan AC. Semua unit memiliki teras pribadi untuk menikmati pemandangan laut dan kamar mandi dalam dengan air tawar.

Area umum termasuk restoran terbuka yang menyajikan hidangan lokal dengan spesialisasi seafood segar, bar kecil yang menyajikan minuman lokal dan internasional, dan area bersantai dengan hammock dan bean bag di tepi pantai. Santiri Bay Cottages memiliki dermaga kecil yang menjadi titik keberangkatan untuk aktivitas snorkeling, memancing, dan island hopping ke pulau-pulau kecil di sekitarnya.

Lokasi yang terpencil memberikan privasi maksimal namun tetap menawarkan pengalaman alam yang autentik. Susasana tenang dan pemandangan matahari terbenam yang spektakuler menjadi daya tarik utama akomodasi ini. Meskipun akses jalan menuju lokasi cukup menantang, pengalaman menginap di sini sangat worth it bagi pencinta alam dan ketenangan.',
                'type' => 'hotel',
                'address' => 'Teluk Santiri, Desa Santiri, Kecamatan Tiworo Selatan',
                'district_id' => $districts->where('name', 'Tiworo Selatan')->first()->id ?? 4,
                'latitude' => -4.962274,
                'longitude' => 122.553345,
                'price_range_start' => 300000,
                'price_range_end' => 700000,
                'facilities' => json_encode([
                    'Restoran',
                    'Bar pantai',
                    'Dermaga pribadi',
                    'Persewaan peralatan snorkeling',
                    'Layanan perahu',
                    'Hammock',
                    'Parkir terbatas'
                ]),
                'contact_person' => 'Manajer Santiri Bay',
                'phone_number' => '087654321098',
                'email' => 'info@santiribay.com',
                'website' => 'santiribay.com',
                'booking_link' => 'santiribay.com/booking',
                'status' => true,
            ],
            [
                'name' => 'Wisma Pemerintah Muna Barat',
                'description' => 'Wisma Pemerintah Muna Barat adalah akomodasi milik pemerintah daerah yang telah direnovasi dan dibuka untuk umum. Wisma ini menawarkan penginapan dengan harga terjangkau tanpa mengorbankan kenyamanan dasar. Awalnya diperuntukkan bagi tamu pemerintah, kini wisma ini menerima wisatawan umum.

Bangunan bergaya kolonial dengan sentuhan arsitektur lokal ini memiliki beberapa tipe kamar dari standar hingga VIP. Semua kamar dilengkapi dengan AC, tempat tidur yang nyaman, dan kamar mandi dalam. Kamar VIP memiliki ruang tamu terpisah dan pemandangan taman yang asri.

Fasilitas wisma mencakup ruang makan bersama yang menyajikan makanan rumahan tiga kali sehari, ruang pertemuan kecil, taman yang luas dan teduh, serta area parkir. Lokasi wisma cukup strategis, dekat dengan pusat pemerintahan dan beberapa destinasi wisata populer di Muna Barat.

Kelebihan menginap di Wisma Pemerintah adalah pelayanannya yang ramah dan kekeluargaan, kebersihan yang terjaga, serta harga yang sangat terjangkau untuk fasilitas yang ditawarkan. Wisma ini cocok untuk wisatawan dengan budget terbatas namun tetap menginginkan akomodasi yang layak dan nyaman.',
                'type' => 'guest house',
                'address' => 'Jl. Kompleks Pemerintahan, Kecamatan Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 3,
                'latitude' => -4.938511,
                'longitude' => 122.547763,
                'price_range_start' => 200000,
                'price_range_end' => 450000,
                'facilities' => json_encode([
                    'AC',
                    'Makan 3x sehari',
                    'Ruang pertemuan',
                    'Taman',
                    'Parkir',
                    'TV umum'
                ]),
                'contact_person' => 'Pengelola Wisma',
                'phone_number' => '082187654321',
                'email' => 'wisma.munabarat@munabarat.go.id',
                'website' => null,
                'booking_link' => null,
                'status' => true,
            ],
        ];

        foreach ($accommodations as $accommodation) {
            try {
                $slug = Str::slug($accommodation['name']);
                $accommodation['slug'] = $slug;

                // Verifikasi bahwa semua kolom dalam data sesuai dengan kolom di tabel
                $availableColumns = Schema::getColumnListing('accommodations');
                $accommodationData = array_intersect_key($accommodation, array_flip($availableColumns));

                $newAccommodation = Accommodation::create($accommodationData);

                // Buat galeri untuk akomodasi
                $galleryCount = rand(3, 6); // 3-6 gambar per akomodasi
                for ($i = 1; $i <= $galleryCount; $i++) {
                    Gallery::create([
                        'imageable_id' => $newAccommodation->id,
                        'imageable_type' => get_class($newAccommodation),
                        'file_path' => 'dummy/accommodations/' . $slug . '_' . $i . '.jpg',
                        'caption' => $accommodation['name'] . ' - ' . ($i === 1 ? 'Exterior' : ($i === 2 ? 'Room' : ($i === 3 ? 'Bathroom' : ($i === 4 ? 'Restaurant' : ($i === 5 ? 'Facilities' : 'View'))))),
                        'is_featured' => ($i === 1), // Gambar pertama adalah featured
                        'order' => $i,
                    ]);
                }

                // Set featured image dari galeri pertama
                $newAccommodation->update([
                    'featured_image' => 'dummy/accommodations/' . $slug . '_1.jpg'
                ]);

                $this->command->info("Akomodasi {$accommodation['name']} berhasil dibuat.");
            } catch (\Exception $e) {
                $this->command->error("Error saat membuat akomodasi {$accommodation['name']}: " . $e->getMessage());
                $this->command->line("Data yang dimasukkan: " . json_encode($accommodation));
            }
        }

        $this->command->info(count($accommodations) . ' akomodasi berhasil ditambahkan!');
    }
}
