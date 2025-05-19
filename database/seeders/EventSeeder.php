<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\District;
use App\Models\Gallery;
use App\Models\CulturalHeritage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada untuk mencegah duplikasi
        if (app()->environment() !== 'production') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Event::truncate();
            \DB::table('cultural_heritage_event')->truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Mendapatkan referensi ke kecamatan yang ada
        $districts = District::all();
        
        // Mendapatkan referensi ke warisan budaya untuk relasi
        $culturalHeritages = CulturalHeritage::all();

        // Data acara cultural
        $culturalEvents = [
            [
                'name' => 'Festival Tarian Linda',
                'description' => 'Festival tahunan yang menampilkan tarian khas Muna Barat "Tari Linda" yang dilakukan oleh puluhan penari dari seluruh kecamatan. Festival ini menjadi salah satu agenda budaya terbesar yang menampilkan keindahan dan keanggunan tarian tradisional masyarakat Muna.

Acara ini juga akan menampilkan berbagai kompetisi tarian Linda antar kelompok seni dari berbagai desa dan kecamatan. Para penari akan mengenakan pakaian adat lengkap dan diiringi musik tradisional Muna yang dimainkan langsung oleh para seniman setempat.

Selain pertunjukan tari, festival ini juga menghadirkan pameran kerajinan tangan, kuliner khas Muna Barat, dan workshop tarian Linda untuk pengunjung yang ingin belajar gerakan dasar tarian ini. Festival ini menjadi ajang pelestarian dan promosi warisan budaya tak benda yang telah diwariskan dari generasi ke generasi.',
                'start_date' => Carbon::now()->addDays(30)->setHour(9)->setMinute(0),
                'end_date' => Carbon::now()->addDays(32)->setHour(21)->setMinute(0),
                'location' => 'Alun-alun Kabupaten Muna Barat',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'organizer' => 'Dinas Kebudayaan dan Pariwisata Kab. Muna Barat',
                'contact_person' => 'Bapak La Ode Kamaluddin',
                'contact_phone' => '085234567890',
                'is_free' => false,
                'ticket_price' => 25000,
                'capacity' => 500,
                'schedule_info' => "Hari 1: 09.00-17.00 WITA - Pembukaan dan Pertunjukan\nHari 2: 10.00-22.00 WITA - Kompetisi Tarian\nHari 3: 10.00-21.00 WITA - Final dan Penutupan",
                'facilities' => "Panggung utama, area penonton berkapasitas 500 orang, toilet umum, area kuliner, tenda istirahat, booth pameran kerajinan, area parkir",
                'is_recurring' => true,
                'recurring_type' => 'yearly',
                'is_featured' => true,
                'status' => true,
                'related_heritages' => ['Tari Linda'],
            ],
            [
                'name' => 'Upacara Kaago-ago (Festival Panen)',
                'description' => 'Upacara adat Kaago-ago merupakan tradisi syukuran panen yang diadakan oleh masyarakat Muna Barat. Acara ini menampilkan berbagai ritual adat, tarian, dan persembahan kepada alam sebagai ungkapan terima kasih atas hasil panen yang melimpah.

Acara dimulai dengan prosesi ritual oleh para Parabela (pemimpin adat) dan Sandro (dukun) yang memimpin pembacaan mantra dan doa tradisional. Kemudian dilanjutkan dengan arak-arakan hasil panen keliling desa yang diikuti oleh seluruh warga dengan mengenakan pakaian adat.

Di acara ini, pengunjung dapat menyaksikan berbagai pertunjukan seni seperti tari Mangaru (tari perang), Kantola (berbalas pantun), dan musik tradisional. Acara puncak adalah makan bersama dari hasil panen yang telah didoakan dan diberkati dalam upacara adat.

Pengunjung juga dapat belajar tentang kearifan lokal masyarakat Muna dalam menjaga keseimbangan dengan alam dan teknik pertanian tradisional yang masih dipraktikkan hingga saat ini.',
                'start_date' => Carbon::now()->addDays(15)->setHour(8)->setMinute(0),
                'end_date' => Carbon::now()->addDays(15)->setHour(17)->setMinute(0),
                'location' => 'Desa Wakobalu Barat',
                'district_id' => $districts->where('name', 'Kusambi')->first()->id ?? 5,
                'organizer' => 'Lembaga Adat Muna Barat dan Desa Wakobalu Barat',
                'contact_person' => 'Bapak La Ode Gusmi',
                'contact_phone' => '082187654321',
                'is_free' => true,
                'ticket_price' => 0,
                'capacity' => 300,
                'schedule_info' => "08.00-10.00 WITA: Ritual Pembuka\n10.00-12.00 WITA: Arak-arakan Hasil Panen\n13.00-15.00 WITA: Pertunjukan Seni\n15.00-17.00 WITA: Makan Bersama dan Penutupan",
                'facilities' => "Area ritual adat, pendopo desa, toilet umum, area parkir",
                'is_recurring' => true,
                'recurring_type' => 'yearly',
                'is_featured' => true,
                'status' => true,
                'related_heritages' => ['Kaago-ago (Ritual Panen)'],
            ],
            [
                'name' => 'Malam Kantola: Festival Berbalas Pantun',
                'description' => 'Malam Kantola adalah acara yang menampilkan tradisi berbalas pantun khas Muna. Para seniman Kantola dari berbagai desa akan saling beradu kepandaian dalam menciptakan dan menyampaikan pantun secara spontan dengan irama khas yang indah.

Kantola merupakan tradisi unik masyarakat Muna yang hampir punah. Festival ini bertujuan untuk melestarikan dan mengenalkan tradisi tersebut kepada generasi muda dan wisatawan. Pantun-pantun yang dibawakan berisi nasihat, sindiran halus, ungkapan cinta, atau kisah-kisah tradisional.

Acara dibagi menjadi beberapa sesi, diawali dengan pertunjukan Kantola oleh para maestro, dilanjutkan dengan kompetisi Kantola antar desa, dan diakhiri dengan Kantola interaktif dimana pengunjung dapat ikut serta mencoba berbalas pantun dengan para seniman.

Suasana festival yang intim dan hangat di bawah cahaya obor dan lampu tradisional memberikan pengalaman budaya yang mendalam dan otentik bagi pengunjung. Makanan dan minuman tradisional juga tersedia selama acara berlangsung.',
                'start_date' => Carbon::now()->addDays(45)->setHour(19)->setMinute(0),
                'end_date' => Carbon::now()->addDays(45)->setHour(23)->setMinute(59),
                'location' => 'Benteng Patua',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'organizer' => 'Komunitas Pelestari Seni Kantola',
                'contact_person' => 'Ibu Wa Ode Sarina',
                'contact_phone' => '081345678901',
                'is_free' => false,
                'ticket_price' => 15000,
                'capacity' => 150,
                'schedule_info' => "19.00-20.00 WITA: Pembukaan dan Pertunjukan Maestro Kantola\n20.00-22.00 WITA: Kompetisi Kantola antar Desa\n22.00-24.00 WITA: Kantola Interaktif",
                'facilities' => "Panggung tradisional, area duduk lesehan, lampu obor, warung kopi tradisional, toilet umum",
                'is_recurring' => false,
                'recurring_type' => null,
                'is_featured' => false,
                'status' => true,
                'related_heritages' => ['Kantola (Tradisi Berpantun)', 'Benteng Patua'],
            ],
            [
                'name' => 'Perayaan Kasambu: Ritual Kelahiran',
                'description' => 'Perayaan Kasambu adalah demonstrasi ritual kelahiran tradisional masyarakat Muna yang digelar secara khusus untuk tujuan edukasi dan pelestarian budaya. Acara ini menampilkan rangkaian ritual yang biasanya dilakukan saat menyambut kelahiran bayi dalam tradisi Muna.

Event ini merupakan rekonstruksi budaya yang memperlihatkan tahapan-tahapan ritual Kasambu, mulai dari persiapan ramuan tradisional, prosesi memandikan bayi, pemotongan rambut pertama, hingga doa-doa dan sedekah yang menyertainya. Semua tahapan dirancang sebagai pembelajaran budaya tanpa melibatkan bayi sesungguhnya.

Selain demonstrasi ritual, acara ini juga menghadirkan pameran peralatan dan perlengkapan tradisional yang digunakan dalam ritual Kasambu, serta diskusi dengan para dukun beranak tradisional (sandro) yang akan berbagi pengetahuan tentang praktik persalinan dan perawatan bayi dalam budaya Muna.

Pengunjung akan mendapatkan pemahaman mendalam tentang filosofi dan nilai-nilai yang terkandung dalam ritual kelahiran Muna, serta bagaimana ritual ini mencerminkan pandangan hidup dan hubungan dengan alam dalam kepercayaan tradisional masyarakat Muna.',
                'start_date' => Carbon::now()->addDays(60)->setHour(10)->setMinute(0),
                'end_date' => Carbon::now()->addDays(60)->setHour(16)->setMinute(0),
                'location' => 'Pendopo Desa Tiga',
                'district_id' => $districts->where('name', 'Wadaga')->first()->id ?? 7,
                'organizer' => 'Dinas Kebudayaan dan Pendidikan Kab. Muna Barat',
                'contact_person' => 'Ibu Wa Ode Nurlia',
                'contact_phone' => '087865432109',
                'is_free' => false,
                'ticket_price' => 10000,
                'capacity' => 100,
                'schedule_info' => "10.00-11.30 WITA: Presentasi tentang Ritual Kasambu\n11.30-13.00 WITA: Istirahat\n13.00-15.00 WITA: Demonstrasi Ritual\n15.00-16.00 WITA: Diskusi dan Penutupan",
                'facilities' => "Pendopo tradisional, kursi penonton, area pameran, toilet umum, area parkir",
                'is_recurring' => false,
                'recurring_type' => null,
                'is_featured' => false,
                'status' => true,
                'related_heritages' => ['Kasambu (Upacara Kelahiran)'],
            ],
            [
                'name' => 'Workshop Tenun Kaseseha',
                'description' => 'Workshop Tenun Kaseseha adalah acara untuk mengenalkan dan mengajarkan seni menenun tradisional Muna kepada peserta. Workshop ini dipimpin oleh para penenun profesional dari Desa Wakobalu yang telah mewarisi keterampilan menenun selama berabad-abad.

Para peserta akan belajar seluruh proses pembuatan kain tenun Muna, mulai dari persiapan benang, pewarnaan alami menggunakan bahan-bahan dari tumbuhan lokal, hingga teknik menenun dengan alat tenun tradisional. Peserta juga akan belajar tentang ragam motif tenun khas Muna dan makna filosofis di balik setiap pola.

Workshop ini dibatasi untuk 20 peserta per sesi untuk memastikan setiap peserta mendapatkan bimbingan yang intensif. Setiap peserta akan membawa pulang hasil tenun sendiri sebagai kenang-kenangan dan bukti keterampilan yang telah dipelajari.

Workshop ini tidak hanya fokus pada aspek teknis menenun, tetapi juga menekankan nilai kultural dan upaya pelestarian warisan budaya tak benda Muna Barat. Peserta akan diajak menghargai proses panjang dan kesabaran yang dibutuhkan dalam menenun kain tradisional.',
                'start_date' => Carbon::now()->addDays(20)->setHour(9)->setMinute(0),
                'end_date' => Carbon::now()->addDays(22)->setHour(16)->setMinute(0),
                'location' => 'Sentra Tenun Desa Wakobalu',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'organizer' => 'Komunitas Penenun Wakobalu',
                'contact_person' => 'Ibu Wa Ode Hasna',
                'contact_phone' => '089876543210',
                'is_free' => false,
                'ticket_price' => 350000,
                'capacity' => 20,
                'schedule_info' => "Hari 1: 09.00-16.00 WITA - Persiapan Benang dan Pewarnaan\nHari 2: 09.00-16.00 WITA - Teknik Dasar Menenun\nHari 3: 09.00-16.00 WITA - Penyelesaian Karya dan Penutupan",
                'facilities' => "Alat tenun tradisional, bahan benang, pewarna alami, makan siang tradisional, sertifikat workshop",
                'is_recurring' => true,
                'recurring_type' => 'monthly',
                'is_featured' => true,
                'status' => true,
                'related_heritages' => ['Kaseseha (Tradisi Menenun)'],
            ],
        ];

        // Data acara tourist attraction
        $touristEvents = [
            [
                'name' => 'Festival Pantai Napabale',
                'description' => 'Festival Pantai Napabale adalah perayaan keindahan dan kekayaan bahari di salah satu pantai terindah Muna Barat. Acara tahunan ini menggabungkan kompetisi olahraga air, pertunjukan seni, kuliner seafood, dan berbagai aktivitas pantai yang menarik.

Pengunjung dapat menikmati beragam kompetisi seperti lomba dayung tradisional, renang jarak jauh, voli pantai, dan menangkap ikan secara tradisional. Festival ini juga menampilkan parade perahu hias yang didekorasi dengan ornamen-ornamen khas Muna.

Di sepanjang pantai akan berjejer aneka stand kuliner yang menyajikan hidangan laut segar khas Muna Barat, seperti ikan bakar bumbu khas, gohu ikan (ceviche khas Sulawesi), dan berbagai olahan seafood lainnya. Workshop pembuatan kerajinan dari bahan laut seperti kerang dan kayu apung juga tersedia bagi pengunjung.

Malam hari akan dimeriahkan dengan pertunjukan musik dan tari di panggung utama, serta pagelaran kembang api spektakuler yang menerangi langit malam pantai Napabale. Festival ini merupakan kesempatan sempurna untuk menikmati keindahan pantai Napabale sambil merasakan kekayaan budaya bahari Muna Barat.',
                'start_date' => Carbon::now()->addDays(75)->setHour(8)->setMinute(0),
                'end_date' => Carbon::now()->addDays(77)->setHour(22)->setMinute(0),
                'location' => 'Pantai Napabale',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? 3,
                'organizer' => 'Dinas Pariwisata dan Pemuda Olahraga Kab. Muna Barat',
                'contact_person' => 'Bapak La Ode Mukmin',
                'contact_phone' => '082398765432',
                'is_free' => true,
                'ticket_price' => 0,
                'capacity' => 1000,
                'schedule_info' => "Hari 1: 08.00-22.00 WITA - Pembukaan dan Lomba Dayung\nHari 2: 08.00-22.00 WITA - Kompetisi Olahraga Pantai\nHari 3: 08.00-22.00 WITA - Parade Perahu dan Penutupan",
                'facilities' => "Panggung utama, area kuliner, toilet dan kamar bilas, tenda peneduh, area parkir, pos kesehatan",
                'is_recurring' => true,
                'recurring_type' => 'yearly',
                'is_featured' => true,
                'status' => true,
                'related_heritages' => [],
            ],
            [
                'name' => 'Ekspedisi Gua Liangkobori',
                'description' => 'Ekspedisi Gua Liangkobori adalah kegiatan petualangan ilmiah untuk menjelajahi dan mempelajari gua prasejarah dengan lukisan gua berusia ribuan tahun yang merupakan salah satu warisan budaya penting di Muna Barat. Ekspedisi ini dipandu oleh arkeolog dan ahli gua yang akan berbagi pengetahuan tentang sejarah dan signifikansi lukisan gua tersebut.

Peserta akan menjelajahi kompleks gua kapur dengan penerangan yang memadai dan peralatan keselamatan standar. Di dalam gua, peserta akan melihat langsung lukisan-lukisan prasejarah yang menggambarkan adegan perburuan, binatang seperti rusa dan babi hutan, serta bentuk geometris dan cap tangan yang dibuat 5000-3000 tahun yang lalu.

Ekspedisi ini juga mencakup penjelasan tentang kondisi geologi gua, formasi stalaktit dan stalagmit, serta kehidupan fauna gua yang unik. Arkeolog akan menjelaskan teori-teori tentang siapa yang membuat lukisan tersebut dan apa artinya dalam konteks peradaban prasejarah di Sulawesi Tenggara.

Kegiatan ini tidak hanya bersifat edukatif namun juga bertujuan meningkatkan kesadaran akan pentingnya melestarikan situs arkeologi yang terancam oleh vandalisme dan erosi alamiah. Peserta dibatasi untuk menjaga kelestarian situs dan memastikan pengalaman yang optimal.',
                'start_date' => Carbon::now()->addDays(40)->setHour(8)->setMinute(0),
                'end_date' => Carbon::now()->addDays(40)->setHour(14)->setMinute(0),
                'location' => 'Gua Liangkobori, Pegunungan Muna',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'organizer' => 'Balai Pelestarian Cagar Budaya Sulawesi Tenggara',
                'contact_person' => 'Dr. Abdul Rahman',
                'contact_phone' => '081234987654',
                'is_free' => false,
                'ticket_price' => 100000,
                'capacity' => 30,
                'schedule_info' => "08.00-08.30 WITA: Briefing keselamatan\n08.30-11.30 WITA: Eksplorasi Gua\n11.30-14.00 WITA: Istirahat, Diskusi, dan Penutupan",
                'facilities' => "Peralatan keselamatan, pemandu ahli, perlengkapan penerangan, bekal makanan dan minuman",
                'is_recurring' => true,
                'recurring_type' => 'monthly',
                'is_featured' => false,
                'status' => true,
                'related_heritages' => ['Gua Prasejarah Liangkobori'],
            ],
            [
                'name' => 'Tour Perkampungan Tradisional Katipalalla',
                'description' => 'Tour Perkampungan Tradisional Katipalalla adalah pengalaman wisata budaya yang membawa pengunjung untuk merasakan kehidupan di perkampungan tradisional yang masih mempertahankan arsitektur dan cara hidup warisan leluhur Muna. Pengunjung akan disambut dengan upacara penyambutan tradisional oleh tetua adat dan warga desa.

Selama tour, pengunjung akan menjelajahi kompleks rumah panggung tradisional berusia ratusan tahun, belajar tentang teknik konstruksi unik yang tidak menggunakan paku, dan filosofi di balik tata ruang tradisional. Para pemandu lokal akan menjelaskan makna simbolis dari berbagai elemen arsitektur dan ornamen yang menghiasi rumah-rumah tersebut.

Pengunjung juga akan diajak berpartisipasi dalam aktivitas sehari-hari masyarakat, seperti mengolah sagu, menenun kain tradisional, atau membuat kerajinan dari bambu dan rotan. Mereka juga akan menikmati pertunjukan seni tradisional seperti tari Linda atau musik tradisional Muna.

Bagian istimewa dari tour ini adalah kesempatan untuk menginap di rumah panggung tradisional dan merasakan langsung kehidupan masyarakat Katipalalla. Makanan tradisional akan disajikan dan pengunjung dapat berbincang dengan warga untuk mengenal lebih dalam tentang adat istiadat dan kearifan lokal mereka.',
                'start_date' => Carbon::now()->addDays(50)->setHour(9)->setMinute(0),
                'end_date' => Carbon::now()->addDays(51)->setHour(15)->setMinute(0),
                'location' => 'Desa Katipalalla',
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id ?? 2,
                'organizer' => 'Komunitas Adat Katipalalla',
                'contact_person' => 'Bapak La Ode Hasiru',
                'contact_phone' => '085678912345',
                'is_free' => false,
                'ticket_price' => 250000,
                'capacity' => 20,
                'schedule_info' => "Hari 1: 09.00-21.00 WITA - Penyambutan, Tour, Aktivitas Budaya\nHari 2: 07.00-15.00 WITA - Aktivitas Pagi, Tour Lanjutan, Penutupan",
                'facilities' => "Penginapan rumah tradisional, makan 3x, pemandu lokal, pertunjukan budaya, workshop kerajinan",
                'is_recurring' => true,
                'recurring_type' => 'weekly',
                'is_featured' => true,
                'status' => true,
                'related_heritages' => ['Perkampungan Tradisional Katipalalla'],
            ],
            [
                'name' => 'Muna Barat Photography Tour',
                'description' => 'Muna Barat Photography Tour adalah tur fotografi khusus yang dirancang untuk fotografer amatir maupun profesional yang ingin mengabadikan keindahan alam, budaya, dan kehidupan di Kabupaten Muna Barat. Tur ini dipandu oleh fotografer profesional lokal yang memahami spot-spot terbaik dan momen paling fotogenik di Muna Barat.

Peserta akan dibawa ke berbagai lokasi terbaik untuk fotografi, termasuk pantai-pantai indah dengan formasi karang unik, perkampungan tradisional dengan rumah panggung khas, situs arkeologi dan sejarah, serta momen-momen aktivitas tradisional masyarakat seperti melaut, menenun, dan upacara adat.

Tour ini dirancang dengan memperhatikan pencahayaan optimal di setiap lokasi, sehingga jadwalnya disesuaikan untuk menangkap golden hour di pagi dan sore hari. Fotografer pemandu akan memberikan tips dan teknik fotografi yang sesuai untuk setiap lokasi dan subjek, serta konteks budaya di balik setiap momen yang diabadikan.

Selain aktivitas fotografi, tur ini juga mencakup workshop pengeditan foto dan sesi review karya setiap malam. Di akhir tour, karya-karya terbaik peserta akan dipamerkan dalam mini ekshibisi dan dihimpun dalam buku digital kenang-kenangan tur.',
                'start_date' => Carbon::now()->addDays(65)->setHour(5)->setMinute(0),
                'end_date' => Carbon::now()->addDays(67)->setHour(21)->setMinute(0),
                'location' => 'Kabupaten Muna Barat',
                'district_id' => $districts->where('name', 'Barangka')->first()->id ?? 4,
                'organizer' => 'Komunitas Fotografer Muna',
                'contact_person' => 'Bapak Rahmat Hidayat',
                'contact_phone' => '081345678909',
                'is_free' => false,
                'ticket_price' => 1500000,
                'capacity' => 15,
                'schedule_info' => "Hari 1: 05.00-21.00 WITA - Pantai dan Desa Nelayan\nHari 2: 05.00-21.00 WITA - Perkampungan Tradisional dan Situs Sejarah\nHari 3: 05.00-21.00 WITA - Kegiatan Budaya dan Exhibition",
                'facilities' => "Transportasi, akomodasi, makan 3x sehari, pemandu fotografi profesional, workshop editing, mini exhibition",
                'is_recurring' => false,
                'recurring_type' => null,
                'is_featured' => false,
                'status' => true,
                'related_heritages' => [],
            ],
        ];

        // Gabungkan kedua array acara
        $events = array_merge($culturalEvents, $touristEvents);

        // Insert data acara
        foreach ($events as $eventData) {
            // Extract related heritages data then remove from event data
            $relatedHeritages = $eventData['related_heritages'] ?? [];
            unset($eventData['related_heritages']);
            
            // Create the event
            $event = Event::create($eventData);
            
            // Create relations with cultural heritage if specified
            if (!empty($relatedHeritages)) {
                foreach ($relatedHeritages as $heritageName) {
                    $heritage = $culturalHeritages->where('name', $heritageName)->first();
                    
                    if ($heritage) {
                        $event->culturalHeritages()->attach($heritage->id);
                    }
                }
            }
            
            // Create dummy gallery images for each event
            $galleryCount = rand(2, 5); // Random number of images between 2-5
            for ($i = 1; $i <= $galleryCount; $i++) {
                $imageName = 'event_' . Str::slug($event->name) . '_' . $i . '.jpg';
                
                Gallery::create([
                    'imageable_id' => $event->id,
                    'imageable_type' => Event::class,
                    'file_path' => 'dummy/events/' . $imageName,
                    'caption' => $event->name . ' - Foto ' . $i,
                    'is_featured' => ($i === 1), // First image is featured
                    'order' => $i,
                ]);
            }
            
            // Set featured image to the first gallery image
            $event->update([
                'featured_image' => 'dummy/events/event_' . Str::slug($event->name) . '_1.jpg',
            ]);
        }

        $this->command->info('Seeder untuk Event berhasil dijalankan!');
    }
}
