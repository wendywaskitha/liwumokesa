<?php

namespace Database\Seeders;

use App\Models\TourGuide;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada untuk mencegah duplikasi
        if (app()->environment() !== 'production') {
            TourGuide::query()->delete();
        }

        // Data pemandu wisata di Muna Barat
        $tourGuides = [
            [
                'name' => 'La Ode Rafi',
                'email' => 'laode.rafi@guidemunatravel.com',
                'phone' => '082234567890',
                'description' => 'La Ode Rafi adalah pemandu wisata berpengalaman yang telah memiliki sertifikasi nasional. Dengan pengalaman lebih dari 7 tahun memandu wisatawan lokal dan mancanegara, Rafi memiliki pengetahuan mendalam tentang sejarah, budaya, dan kearifan lokal masyarakat Muna Barat.

Sebagai penduduk asli Muna, Rafi menguasai bahasa lokal dan tradisi setempat yang memungkinkannya memberikan wawasan unik tentang kehidupan masyarakat dan kebudayaan Muna. Ia juga fasih berbahasa Inggris dan memiliki kemampuan dasar bahasa Jepang untuk memudahkan komunikasi dengan wisatawan internasional.

Rafi adalah spesialis dalam tur budaya dan sejarah, dengan pengetahuan mendalam tentang situs-situs arkeologi dan warisan budaya di seluruh Muna Barat. Keramahan dan dedikasinya dalam memberikan pengalaman wisata yang berkesan telah mendapatkan banyak pujian dari wisatawan sebelumnya.',
                'photo' => 'dummy/tour_guides/guide_male_1.jpg',
                'languages' => ['Indonesia', 'Muna', 'English', 'Japanese (Basic)'],
                'experience_years' => 7,
                'rating' => 4.8,
                'is_available' => true,
                'status' => true,
            ],
            [
                'name' => 'Wa Ode Sarina',
                'email' => 'waode.sarina@guidemunatravel.com',
                'phone' => '081345678901',
                'description' => 'Wa Ode Sarina adalah pemandu wisata wanita yang berspesialisasi dalam wisata budaya dan kuliner di Muna Barat. Dengan latar belakang pendidikan antropologi dan pengalaman 5 tahun sebagai pemandu wisata, Sarina memiliki pengetahuan mendalam tentang tradisi dan kearifan lokal masyarakat Muna.

Sarina sangat fasih dalam menjelaskan berbagai aspek budaya Muna seperti tarian tradisional, ritual adat, dan kerajinan tenun khas Muna. Sebagai pemandu wisata wanita, ia juga memiliki sensitivitas khusus terhadap kebutuhan wisatawan wanita dan keluarga, menjadikannya sangat populer untuk tur keluarga dan wisata edukasi.

Keahlian kuliner Sarina membuatnya menjadi pemandu yang sempurna untuk wisata kuliner, mengantarkan wisatawan menjelajahi hidangan khas Muna yang otentik dan tersembunyi. Ia juga sering diminta untuk memimpin workshop budaya seperti tenunan tradisional dan pembuatan makanan khas Muna.

Bahasa yang dikuasai meliputi Bahasa Indonesia, Bahasa Muna, dan Bahasa Inggris, sehingga dapat melayani wisatawan internasional dengan baik.',
                'photo' => 'dummy/tour_guides/guide_female_1.jpg',
                'languages' => ['Indonesia', 'Muna', 'English'],
                'experience_years' => 5,
                'rating' => 4.9,
                'is_available' => true,
                'status' => true,
            ],
            [
                'name' => 'Ikhsan Ramadhan',
                'email' => 'ikhsan@adventuremuna.com',
                'phone' => '082187654321',
                'description' => 'Ikhsan Ramadhan adalah pemandu wisata petualangan yang berspesialisasi dalam aktivitas outdoor dan ekowisata di kawasan Muna Barat. Dengan pengalaman 6 tahun memimpin ekspedisi trekking, climbing, diving, dan snorkeling, Ikhsan sangat mengenal medan dan kondisi alam di seluruh Muna Barat.

Berlatar belakang pendidikan kehutanan dan sertifikasi penyelamatan darurat, Ikhsan memastikan keselamatan dan kenyamanan wisatawan dalam setiap petualangan. Ia memiliki pengetahuan mendalam tentang flora, fauna, dan ekosistem di Muna Barat, menjadikannya pemandu ideal untuk wisata edukasi lingkungan dan konservasi.

Ikhsan sering memandu ekspedisi fotografi alam dan penelitian ekologi di kawasan hutan, gua, dan terumbu karang Muna Barat. Kemampuan teknis dan pengetahuan lokalnya sangat dihargai oleh fotografer profesional dan peneliti yang mengunjungi kawasan ini.

Selain bahasa Indonesia dan Muna, Ikhsan juga fasih berbahasa Inggris dan memiliki kemampuan dasar bahasa Mandarin, memudahkannya berkomunikasi dengan wisatawan internasional.',
                'photo' => 'dummy/tour_guides/guide_male_2.jpg',
                'languages' => ['Indonesia', 'Muna', 'English', 'Mandarin (Basic)'],
                'experience_years' => 6,
                'rating' => 4.7,
                'is_available' => true,
                'status' => true,
            ],
            [
                'name' => 'Nur Fadilla',
                'email' => 'nurfadilla@munatourguide.com',
                'phone' => '085678901234',
                'description' => 'Nur Fadilla adalah pemandu wisata muda yang berspesialisasi dalam wisata edukasi dan wisata berkelanjutan di Muna Barat. Dengan latar belakang pendidikan pariwisata dan pengalaman 3 tahun sebagai pemandu, Fadilla membawa pendekatan modern dan ramah lingkungan dalam setiap tur yang dipimpinnya.

Sebagai generasi muda Muna yang peduli lingkungan, Fadilla aktif dalam kampanye pelestarian alam dan budaya Muna Barat. Ia sering memimpin program wisata edukasi untuk sekolah dan universitas yang fokus pada konservasi dan pemberdayaan masyarakat lokal.

Fadilla sangat menguasai media sosial dan fotografi, membuatnya menjadi pilihan favorit bagi wisatawan milenial dan gen Z. Ia selalu memastikan wisatawan mendapatkan pengalaman otentik sekaligus konten media sosial yang menarik dari setiap kunjungan.

Dengan kemampuan berbahasa Indonesia, Muna, Inggris, dan Korea dasar, Fadilla dapat melayani berbagai wisatawan dengan nyaman dan profesional.',
                'photo' => 'dummy/tour_guides/guide_female_2.jpg',
                'languages' => ['Indonesia', 'Muna', 'English', 'Korean (Basic)'],
                'experience_years' => 3,
                'rating' => 4.6,
                'is_available' => true,
                'status' => true,
            ],
            [
                'name' => 'Abdul Karim',
                'email' => 'abdulkarim@munaheritaguide.com',
                'phone' => '081234987654',
                'description' => 'Abdul Karim adalah pemandu wisata senior dengan pengalaman lebih dari 10 tahun dalam memandu wisatawan di Muna Barat dan sekitarnya. Dikenal sebagai "ensiklopedia berjalan" tentang Muna, Abdul memiliki pengetahuan mendalam tentang sejarah, arkeologi, dan budaya Muna yang telah diwariskan dari generasi ke generasi.

Sebagai mantan peneliti di Balai Pelestarian Cagar Budaya, Abdul memiliki wawasan akademis yang kuat tentang situs-situs arkeologi di Muna Barat, terutama lukisan gua prasejarah dan benteng peninggalan kerajaan Muna. Keahliannya dalam konteks sejarah regional dan nasional memberikan perspektif yang komprehensif kepada wisatawan.

Abdul sering diundang sebagai pembicara dalam konferensi warisan budaya dan sering berkolaborasi dengan arkeolog dan sejarawan yang meneliti Muna Barat. Pengalamannya yang luas dan jaringan kontak yang luas memungkinkannya untuk memberikan pengalaman wisata yang mendalam dan personal.

Dengan kemampuan berbahasa Indonesia, Muna, Buton, Inggris, dan Belanda dasar (untuk keperluan penelitian arsip kolonial), Abdul dapat melayani berbagai wisatawan termasuk peneliti dan wisatawan budaya serius.',
                'photo' => 'dummy/tour_guides/guide_male_3.jpg',
                'languages' => ['Indonesia', 'Muna', 'Buton', 'English', 'Dutch (Basic)'],
                'experience_years' => 10,
                'rating' => 4.9,
                'is_available' => true,
                'status' => true,
            ]
        ];

        foreach ($tourGuides as $guideData) {
            // Buat dummy photo jika belum ada
            $photoPath = $guideData['photo'];
            unset($guideData['photo']);

            // Konversi array language menjadi json
            if (isset($guideData['languages'])) {
                $guideData['languages'] = json_encode($guideData['languages']);
            }

            // Buat record tour guide
            $tourGuide = TourGuide::create($guideData);

            // Update photo path
            if (isset($photoPath)) {
                // Check if we're using dummy photo or not
                if (Str::startsWith($photoPath, 'dummy/')) {
                    // Set dummy path directly without creating actual file
                    $tourGuide->update([
                        'photo' => $photoPath
                    ]);
                }
            }

            $this->command->info("Tour guide '{$tourGuide->name}' berhasil dibuat.");
        }

        $this->command->info('Seeder untuk TourGuide berhasil dijalankan!');
    }
}
