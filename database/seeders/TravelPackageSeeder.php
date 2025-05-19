<?php

namespace Database\Seeders;

use App\Models\TravelPackage;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\Transportation;
use App\Models\District;
use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TravelPackageSeeder extends Seeder
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

                // Hapus data dari tabel pivot terlebih dahulu jika ada (sesuaikan nama tabel pivot berdasarkan model)
                if (Schema::hasTable('destination_travel_package')) {
                    DB::table('destination_travel_package')->delete();
                }

                if (Schema::hasTable('accommodation_travel_package')) {
                    DB::table('accommodation_travel_package')->delete();
                }

                if (Schema::hasTable('travel_package_transportation')) {
                    DB::table('travel_package_transportation')->delete();
                }

                // Hapus galleries yang terkait dengan travel packages
                Gallery::where('imageable_type', 'App\Models\TravelPackage')->delete();

                // Sekarang aman untuk menghapus travel packages
                TravelPackage::truncate();

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

                if (Schema::hasTable('accommodation_travel_package')) {
                    DB::table('accommodation_travel_package')->delete();
                }

                if (Schema::hasTable('travel_package_transportation')) {
                    DB::table('travel_package_transportation')->delete();
                }

                // Hapus galleries yang terkait dengan travel packages
                Gallery::where('imageable_type', 'App\Models\TravelPackage')->delete();

                // Hapus data travel packages satu per satu
                TravelPackage::query()->delete();
            }
        }

        // Mendapatkan data untuk relasi
        $districts = District::all();
        $destinations = Destination::all();
        $accommodations = Accommodation::all();
        $transportations = Transportation::all();

        if ($districts->isEmpty() || $destinations->isEmpty()) {
            $this->command->error('Tidak ada district atau destination yang tersedia. Silahkan jalankan DistrictSeeder dan DestinationSeeder terlebih dahulu.');
            return;
        }

        // Array paket perjalanan di Muna Barat (disesuaikan dengan model TravelPackage)
        $travelPackages = [
            [
                'name' => 'Pesona Pantai Muna Barat',
                'description' => 'Nikmati keindahan pantai-pantai eksotis di Muna Barat dengan pemandangan pasir putih, air laut yang jernih, dan terumbu karang yang menakjubkan. Paket ini menawarkan pengalaman lengkap untuk menikmati keindahan bahari Muna Barat, mulai dari snorkeling di terumbu karang, berenang di perairan jernih, hingga bersantai di pantai dengan pasir putih lembut.

Selama perjalanan, Anda akan diajak mengunjungi beberapa pantai terbaik di Muna Barat seperti Pantai Napabale, Pantai Wa Ndiu-Ndiu, dan Pantai Santiri dengan keunikannya masing-masing. Anda juga akan menikmati kuliner seafood segar langsung di pinggir pantai dan menyaksikan matahari terbenam yang memukau dari tepi pantai.

Paket ini cocok untuk wisatawan yang mencari petualangan bahari sekaligus relaksasi di tengah keindahan alam. Dengan akomodasi yang nyaman dan transportasi yang teratur, Anda dapat fokus menikmati keindahan pantai-pantai Muna Barat tanpa perlu khawatir tentang detail perjalanan.',
                'highlights' => 'Snorkeling di terumbu karang indah, Menikmati sunset di pantai, Kuliner seafood segar',
                'duration' => 3,
                'duration_unit' => 1, // hari
                'price' => 1500000,
                'discount_price' => 1350000,
                'inclusions' => [
                    'Transportasi selama perjalanan (mobil AC)',
                    'Akomodasi 2 malam di penginapan tepi pantai (twin share)',
                    'Makan 3x sehari sesuai program',
                    'Air mineral selama perjalanan',
                    'Tiket masuk destinasi wisata',
                    'Perlengkapan snorkeling',
                    'Guide lokal berpengalaman'
                ],
                'exclusions' => [
                    'Tiket pesawat dari dan ke kota asal',
                    'Pengeluaran pribadi',
                    'Tipping untuk guide dan sopir',
                    'Asuransi perjalanan',
                    'Makanan di luar program'
                ],
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Tiba di Muna Barat & Pantai Napabale',
                        'description' => 'Kedatangan di Bandara Sugimanuru Muna, perjalanan menuju penginapan di tepi pantai, makan siang, dan mengunjungi Pantai Napabale untuk snorkeling dan menikmati sunset.'
                    ],
                    [
                        'day' => 2,
                        'title' => 'Eksplorasi Pantai Wa Ndiu-Ndiu',
                        'description' => 'Sarapan di penginapan, kemudian berkunjung ke Pantai Wa Ndiu-Ndiu untuk berenang dan aktivitas beach games. Makan siang di tepi pantai dan mengunjungi kampung nelayan untuk melihat kehidupan masyarakat pesisir.'
                    ],
                    [
                        'day' => 3,
                        'title' => 'Pantai Santiri dan Kembali',
                        'description' => 'Sarapan di penginapan, check-out, mengunjungi Pantai Santiri dengan pasir putih dan formasi batu karang yang unik. Makan siang dan perjalanan kembali ke bandara untuk penerbangan kepulangan.'
                    ]
                ],
                'terms_conditions' => "- Minimal 2 peserta untuk keberangkatan.
- Pembayaran 50% untuk booking, pelunasan H-7 sebelum keberangkatan.
- Itinerary dapat berubah menyesuaikan kondisi cuaca.
- Peserta wajib mengikuti arahan pemandu wisata untuk keselamatan.",
                'meeting_point' => 'Bandara Sugimanuru Muna',
                'min_participants' => 2,
                'max_participants' => 15,
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? 1,
                'difficulty' => 'easy',
                'is_private' => false,
                'is_featured' => true,
                'status' => true,
                'destinations_data' => [
                    // Data destinasi yang akan dihubungkan dengan pivot
                    // Nama destinasi => [day, order, notes]
                    'Pantai Napabale' => [1, 1, 'Snorkeling dan sunset'],
                    'Pantai Wa Ndiu-Ndiu' => [2, 1, 'Berenang dan beach games'],
                    'Pantai Santiri' => [3, 1, 'Eksplorasi formasi karang']
                ],
                'accommodations_data' => [
                    // Data akomodasi yang akan dihubungkan dengan pivot
                    // Nama akomodasi => [day, notes]
                    'Napabale Beach Resort' => [1, 'Check-in mulai jam 14:00'],
                    'Napabale Beach Resort' => [2, 'Breakfast included']
                ],
                'transportations_data' => [
                    // Data transportasi yang akan dihubungkan dengan pivot
                    // Nama transportasi => [route_details, notes]
                    'Rental Mobil Muna Explore' => ['Airport - Hotel - Destinations', 'AC Car with experienced driver'],
                ]
            ],
            [
                'name' => 'Eksplorasi Budaya dan Alam Muna Barat',
                'description' => 'Paket wisata yang menggabungkan keindahan alam dan kekayaan budaya Kabupaten Muna Barat. Selama 4 hari 3 malam, Anda akan diajak untuk mengunjungi berbagai situs sejarah, menyaksikan kesenian tradisional, sekaligus menikmati keindahan alam yang tersembunyi di pelosok Muna Barat.

Perjalanan dimulai dengan mengunjungi Benteng Patua, benteng peninggalan Kerajaan Muna dari abad ke-16 yang menyimpan cerita sejarah panjang. Anda juga akan diajak mengunjungi Perkampungan Tradisional Katipalalla untuk menyaksikan langsung kehidupan masyarakat adat dan arsitektur rumah panggung yang dibangun tanpa paku.

Tidak hanya wisata budaya, paket ini juga mencakup eksplorasi alam dengan mengunjungi Gua Liangkobori untuk melihat lukisan prasejarah, serta trekking ke Air Terjun Lapolea yang tersembunyi di tengah hutan. Di tengah perjalanan, Anda akan disuguhi pertunjukan tari Linda dan Kantola serta workshop kerajinan tenun tradisional.

Paket ini sempurna untuk wisatawan yang mencari pengalaman mendalam tentang budaya dan alam Muna Barat dengan panduan dari pemandu lokal yang berpengalaman.',
                'highlights' => 'Kunjungan ke Benteng Patua, Menginap di rumah tradisional, Melihat lukisan prasejarah di Gua Liangkobori, Workshop tenun tradisional',
                'duration' => 4,
                'duration_unit' => 1, // hari
                'price' => 2800000,
                'discount_price' => null,
                'inclusions' => [
                    'Transportasi darat selama perjalanan (mobil AC)',
                    'Akomodasi 2 malam di penginapan dan 1 malam di rumah tradisional',
                    'Makan 3x sehari sesuai program',
                    'Air mineral selama perjalanan',
                    'Tiket masuk semua destinasi wisata',
                    'Workshop tenun tradisional (termasuk bahan)',
                    'Guide lokal berpengalaman dan interpreter bahasa'
                ],
                'exclusions' => [
                    'Tiket kapal/pesawat dari dan ke kota asal',
                    'Pengeluaran pribadi',
                    'Tipping untuk guide dan sopir',
                    'Asuransi perjalanan',
                    'Makanan di luar program'
                ],
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Tiba di Muna & Benteng Patua',
                        'description' => 'Tiba di Pelabuhan Raha, perjalanan menuju penginapan di Lawa, mengunjungi Benteng Patua, dan menikmati pertunjukan tari tradisional di malam hari.'
                    ],
                    [
                        'day' => 2,
                        'title' => 'Perkampungan Tradisional Katipalalla',
                        'description' => 'Sarapan di penginapan, berkunjung ke Perkampungan Tradisional Katipalalla, mengikuti workshop tenun tradisional, dan menginap di rumah panggung tradisional.'
                    ],
                    [
                        'day' => 3,
                        'title' => 'Gua Liangkobori & Air Terjun',
                        'description' => 'Sarapan di perkampungan, lalu mengunjungi Gua Prasejarah Liangkobori untuk melihat lukisan dinding gua. Dilanjutkan dengan trekking ke Air Terjun Lapolea dan mengunjungi kampung pengrajin tradisional.'
                    ],
                    [
                        'day' => 4,
                        'title' => 'Pantai dan Kepulangan',
                        'description' => 'Sarapan di penginapan, check-out, mengunjungi Pantai Napabale untuk bersantai sebelum perjalanan kembali ke Pelabuhan Raha untuk kepulangan.'
                    ]
                ],
                'terms_conditions' => "- Minimal 4 peserta untuk keberangkatan.
- Pembayaran 50% untuk booking, pelunasan H-14 sebelum keberangkatan.
- Itinerary dapat berubah menyesuaikan kondisi cuaca dan acara adat.
- Peserta wajib menghormati adat istiadat dan kepercayaan lokal.",
                'meeting_point' => 'Pelabuhan Raha, Muna',
                'min_participants' => 4,
                'max_participants' => 12,
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 3,
                'difficulty' => 'moderate',
                'is_private' => false,
                'is_featured' => true,
                'status' => true,
                'destinations_data' => [
                    // Data destinasi yang akan dihubungkan dengan pivot
                    // Nama destinasi => [day, order, notes]
                    'Benteng Patua' => [1, 1, 'Kunjungan benteng dan museum'],
                    'Perkampungan Tradisional Katipalalla' => [2, 1, 'Menginap di rumah tradisional'],
                    'Gua Liangkobori' => [3, 1, 'Melihat lukisan prasejarah'],
                    'Air Terjun Lapolea' => [3, 2, 'Trekking dan berenang'],
                    'Pantai Napabale' => [4, 1, 'Relaksasi sebelum pulang']
                ],
                'accommodations_data' => [
                    // Data akomodasi yang akan dihubungkan dengan pivot
                    // Nama akomodasi => [day, notes]
                    'Hotel Kabupaten Muna Barat' => [1, 'Standard room'],
                    'Katipalalla Homestay' => [2, 'Rumah tradisional'],
                    'Hotel Kabupaten Muna Barat' => [3, 'Standard room']
                ],
                'transportations_data' => [
                    // Data transportasi yang akan dihubungkan dengan pivot
                    // Nama transportasi => [route_details, notes]
                    'Rental Mobil Muna Explore' => ['Port - Hotel - Destinations - Port', 'AC Car with experienced driver'],
                ]
            ]
        ];

        foreach ($travelPackages as $packageData) {
            try {
                // Extract relationship data before creating the package
                $destinationsData = $packageData['destinations_data'] ?? [];
                $accommodationsData = $packageData['accommodations_data'] ?? [];
                $transportationsData = $packageData['transportations_data'] ?? [];

                // Remove relationship data from the package data
                unset($packageData['destinations_data'], $packageData['accommodations_data'], $packageData['transportations_data']);

                // Create slug
                $packageData['slug'] = Str::slug($packageData['slug'] ?? $packageData['name']);

                // Convert arrays to JSON for JSON fields
                if (isset($packageData['inclusions']) && is_array($packageData['inclusions'])) {
                    $packageData['inclusions'] = json_encode($packageData['inclusions']);
                }

                if (isset($packageData['exclusions']) && is_array($packageData['exclusions'])) {
                    $packageData['exclusions'] = json_encode($packageData['exclusions']);
                }

                if (isset($packageData['itinerary']) && is_array($packageData['itinerary'])) {
                    $packageData['itinerary'] = json_encode($packageData['itinerary']);
                }

                // Verify available columns
                $availableColumns = Schema::getColumnListing('travel_packages');
                $packageData = array_intersect_key($packageData, array_flip($availableColumns));

                // Create the package
                $travelPackage = TravelPackage::create($packageData);

                // Attach destinations with pivot data
                foreach ($destinationsData as $destinationName => $pivotData) {
                    $destination = $destinations->where('name', $destinationName)->first();
                    if ($destination) {
                        $day = $pivotData[0] ?? 1;
                        $order = $pivotData[1] ?? 1;
                        $notes = $pivotData[2] ?? null;

                        $travelPackage->destinations()->attach($destination->id, [
                            'day' => $day,
                            'order' => $order,
                            'notes' => $notes
                        ]);
                    }
                }

                // Attach accommodations with pivot data
                foreach ($accommodationsData as $accommodationName => $pivotData) {
                    $accommodation = $accommodations->where('name', $accommodationName)->first();
                    if ($accommodation) {
                        $day = $pivotData[0] ?? 1;
                        $notes = $pivotData[1] ?? null;

                        $travelPackage->accommodations()->attach($accommodation->id, [
                            'day' => $day,
                            'notes' => $notes
                        ]);
                    }
                }

                // Attach transportations with pivot data
                foreach ($transportationsData as $transportationName => $pivotData) {
                    $transportation = $transportations->where('name', $transportationName)->first();
                    if ($transportation) {
                        $routeDetails = $pivotData[0] ?? null;
                        $notes = $pivotData[1] ?? null;

                        $travelPackage->transportations()->attach($transportation->id, [
                            'route_details' => $routeDetails,
                            'notes' => $notes
                        ]);
                    }
                }

                // Create gallery images for the travel package
                $galleryCount = rand(4, 8); // 4-8 images per travel package
                for ($i = 1; $i <= $galleryCount; $i++) {
                    $slug = Str::slug($travelPackage->name);
                    Gallery::create([
                        'imageable_id' => $travelPackage->id,
                        'imageable_type' => get_class($travelPackage),
                        'file_path' => 'dummy/travel_packages/' . $slug . '_' . $i . '.jpg',
                        'caption' => $travelPackage->name . ' - ' . ($i === 1 ? 'Cover' : ($i === 2 ? 'Highlights' : ($i === 3 ? 'Activities' : 'Tour'))),
                        'is_featured' => ($i === 1), // First image is featured
                        'order' => $i,
                    ]);
                }

                // Set featured image from the first gallery image
                $travelPackage->update([
                    'featured_image' => 'dummy/travel_packages/' . Str::slug($travelPackage->name) . '_1.jpg'
                ]);

                $this->command->info("Travel Package '{$travelPackage->name}' berhasil dibuat.");
            } catch (\Exception $e) {
                $this->command->error("Error saat membuat travel package '{$packageData['name']}': " . $e->getMessage());
            }
        }

        $this->command->info(count($travelPackages) . ' travel packages berhasil ditambahkan!');
    }
}
