<?php

namespace Database\Seeders;

use App\Models\CulturalHeritage;
use App\Models\District;
use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CulturalHeritageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada untuk mencegah duplikasi
        if (app()->environment() !== 'production') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            CulturalHeritage::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Mendapatkan referensi ke kecamatan yang ada
        $districts = District::all();

        // Data warisan budaya tangible (berwujud)
        $tangibleHeritages = [
            [
                'name' => 'Benteng Patua',
                'type' => 'tangible',
                'description' => 'Benteng Patua adalah benteng peninggalan Kerajaan Muna yang didirikan sekitar abad ke-16. Benteng ini merupakan pusat pertahanan Kerajaan Muna dan terbuat dari susunan batu karang yang kokoh. Struktur benteng ini mencakup area yang cukup luas dengan dinding yang tebal dan tinggi. Benteng Patua menjadi saksi bisu kejayaan Kerajaan Muna dan perjuangan melawan penjajah.',
                'historical_significance' => 'Benteng Patua memiliki nilai historis yang tinggi sebagai pusat pertahanan Kerajaan Muna melawan serangan dari luar, termasuk serangan dari Kesultanan Buton dan penjajah Belanda. Benteng ini juga berfungsi sebagai pusat aktivitas pemerintahan dan militer Kerajaan Muna pada masa kejayaannya.',
                'location' => 'Desa Wasolangka, Kecamatan Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'latitude' => -4.939511,
                'longitude' => 122.548763,
                'conservation_status' => 'fair',
                'recognition_status' => 'national',
                'physical_description' => 'Benteng berbentuk persegi panjang dengan dinding batu karang setinggi 3-4 meter dan ketebalan sekitar 1,5 meter. Memiliki empat pintu gerbang utama yang menghadap ke empat penjuru mata angin. Di dalam kompleks benteng terdapat sisa-sisa struktur bangunan yang diperkirakan sebagai tempat tinggal raja dan bangsawan serta bangunan untuk keperluan militer.',
                'custodian' => 'Dinas Kebudayaan dan Pariwisata Kabupaten Muna Barat',
                'visitor_info' => 'Benteng dapat dikunjungi setiap hari dari pukul 08.00-18.00 WITA. Pengunjung disarankan mengenakan sepatu yang nyaman karena medan yang tidak rata. Terdapat pemandu lokal yang dapat menjelaskan sejarah benteng.',
                'is_endangered' => false,
                'allows_visits' => true,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'name' => 'Perkampungan Tradisional Katipalalla',
                'type' => 'tangible',
                'description' => 'Perkampungan Tradisional Katipalalla merupakan perkampungan adat yang masih mempertahankan arsitektur tradisional rumah panggung khas Muna. Beberapa rumah di perkampungan ini berusia ratusan tahun dengan struktur kayu yang kuat tanpa menggunakan paku. Sistem pemasangan rumah menggunakan teknik pasak dan ikat yang menunjukkan keahlian arsitektur tradisional masyarakat Muna.',
                'historical_significance' => 'Perkampungan ini telah berdiri sejak awal abad ke-18 dan menjadi pusat kehidupan masyarakat adat Muna. Perkampungan ini menjadi benteng terakhir pelestarian arsitektur tradisional dan pola kehidupan masyarakat Muna sebelum masuknya pengaruh modernisasi.',
                'location' => 'Desa Katipalalla, Kecamatan Sawerigadi',
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id ?? 2,
                'latitude' => -4.956821,
                'longitude' => 122.485043,
                'conservation_status' => 'good',
                'recognition_status' => 'regional',
                'physical_description' => 'Kompleks perkampungan tradisional yang terdiri dari sekitar 25 rumah panggung dengan karakteristik khas Muna. Rumah panggung ini memiliki ketinggian sekitar 2 meter dari tanah dengan tiang-tiang kayu yang kokoh. Rumah-rumah tersebut memiliki struktur atap tinggi berbentuk limas dengan penutup atap dari daun rumbia atau seng. Bagian dalam rumah terbagi menjadi beberapa ruangan termasuk ruang tamu, ruang keluarga, ruang tidur, dan dapur.',
                'custodian' => 'Komunitas Adat Katipalalla',
                'visitor_info' => 'Perkampungan dapat dikunjungi setiap hari, namun sebaiknya menghubungi kepala adat terlebih dahulu. Pengunjung diharapkan menghormati adat istiadat setempat dan tidak mengambil foto tanpa izin. Terdapat homestay tradisional bagi pengunjung yang ingin menginap dan merasakan kehidupan tradisional.',
                'is_endangered' => true,
                'allows_visits' => true,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'name' => 'Situs Megalitik Tanjung Batu',
                'type' => 'tangible',
                'description' => 'Situs Megalitik Tanjung Batu adalah kompleks peninggalan prasejarah berupa batu-batu besar yang tersusun dalam formasi tertentu. Situs ini menunjukkan adanya peradaban prasejarah yang sudah maju di wilayah Muna Barat sejak ribuan tahun yang lalu. Batu-batu ini dipercaya memiliki fungsi ritual dan astronomis bagi masyarakat prasejarah.',
                'historical_significance' => 'Situs megalitik ini diperkirakan dibangun pada zaman neolitikum akhir (sekitar 2500-1500 SM) dan merupakan bukti penting keberadaan komunitas prasejarah yang telah memiliki sistem kepercayaan dan pengetahuan astronomi yang maju di Sulawesi Tenggara.',
                'location' => 'Tanjung Batu, Kecamatan Tiworo Utara',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id ?? 3,
                'latitude' => -4.962174,
                'longitude' => 122.553245,
                'conservation_status' => 'poor',
                'recognition_status' => 'regional',
                'physical_description' => 'Kompleks megalitik yang terdiri dari sekitar 40 batu besar berbentuk menhir dengan tinggi bervariasi antara 0,5-2,5 meter. Batu-batu ini tersusun dalam formasi melingkar dan beberapa membentuk pola baris. Sebagian batu memiliki ukiran sederhana berupa garis-garis dan lingkaran. Di sekitar situs juga ditemukan beberapa artefak seperti pecahan gerabah dan alat batu.',
                'custodian' => 'Balai Pelestarian Cagar Budaya Sulawesi Tenggara',
                'visitor_info' => 'Situs dapat dikunjungi setiap hari, namun disarankan membawa pemandu lokal karena lokasi yang cukup tersembunyi. Pengunjung diminta tidak memindahkan atau merusak batu-batu di situs.',
                'is_endangered' => true,
                'allows_visits' => true,
                'is_featured' => false,
                'status' => true,
            ],
            [
                'name' => 'Masjid Tua Al-Mujahidin',
                'type' => 'tangible',
                'description' => 'Masjid Tua Al-Mujahidin merupakan masjid tertua di Muna Barat yang dibangun pada awal abad ke-18. Masjid ini menjadi pusat penyebaran agama Islam di wilayah tersebut. Arsitektur masjid ini menggabungkan unsur tradisional Muna dengan pengaruh Islam dari Malaka dan Jawa.',
                'historical_significance' => 'Masjid ini menjadi saksi sejarah masuknya Islam di tanah Muna dan berperan penting dalam penyebaran ajaran Islam di Sulawesi Tenggara. Pembangunannya diprakarsai oleh ulama besar yang datang dari Malaka pada masa pemerintahan Raja Muna ke-12.',
                'location' => 'Desa Lahontohe, Kecamatan Barangka',
                'district_id' => $districts->where('name', 'Barangka')->first()->id ?? 4,
                'latitude' => -4.921083,
                'longitude' => 122.563152,
                'conservation_status' => 'good',
                'recognition_status' => 'regional',
                'physical_description' => 'Bangunan masjid berbentuk persegi dengan atap tumpang tiga yang khas. Struktur utama terbuat dari kayu jati dengan tiang-tiang penyangga yang kokoh. Ukiran tradisional Muna menghiasi mimbar dan beberapa bagian interior masjid. Masjid dapat menampung sekitar 200 jamaah dengan serambi yang luas di bagian depan. Meskipun telah mengalami beberapa kali renovasi, struktur dan elemen asli masjid tetap dipertahankan.',
                'custodian' => 'Pengurus Masjid Al-Mujahidin dan Masyarakat Desa Lahontohe',
                'visitor_info' => 'Masjid dapat dikunjungi setiap hari kecuali saat waktu sholat. Pengunjung diminta berpakaian sopan dan tidak mengganggu kegiatan ibadah.',
                'is_endangered' => false,
                'allows_visits' => true,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'name' => 'Gua Prasejarah Liangkobori',
                'type' => 'tangible',
                'description' => 'Gua Liangkobori merupakan situs prasejarah yang memiliki lukisan gua berusia ribuan tahun. Lukisan-lukisan tersebut menggambarkan aktivitas berburu, binatang, dan simbol-simbol ritual yang memberikan gambaran tentang kehidupan manusia prasejarah di wilayah Muna Barat.',
                'historical_significance' => 'Lukisan gua di Liangkobori diperkirakan berasal dari 5000-3000 SM dan merupakan bukti keberadaan peradaban prasejarah di Sulawesi Tenggara. Lukisan ini menjadi sumber informasi penting tentang pola hidup, kepercayaan, dan kebudayaan manusia prasejarah di kawasan ini.',
                'location' => 'Pegunungan Muna, Kecamatan Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'latitude' => -4.932451,
                'longitude' => 122.539872,
                'conservation_status' => 'critical',
                'recognition_status' => 'national',
                'physical_description' => 'Kompleks gua kapur dengan beberapa ruangan besar dan lorong-lorong kecil. Lukisan gua terdapat pada dinding-dinding gua utama dengan pigmen berwarna merah dan hitam. Lukisan tersebut menggambarkan adegan perburuan, binatang seperti rusa dan babi hutan, serta beberapa bentuk geometris dan cap tangan. Gua memiliki stalaktit dan stalagmit yang masih aktif tumbuh.',
                'custodian' => 'Balai Pelestarian Cagar Budaya Sulawesi Tenggara',
                'visitor_info' => 'Pengunjung harus didampingi pemandu resmi. Dilarang menyentuh lukisan gua atau menggunakan flash saat mengambil foto. Akses menuju gua cukup menantang dan membutuhkan kondisi fisik yang baik.',
                'is_endangered' => true,
                'allows_visits' => true,
                'is_featured' => false,
                'status' => true,
            ],
        ];

        // Data warisan budaya intangible (tak berwujud)
        $intangibleHeritages = [
            [
                'name' => 'Tari Linda',
                'type' => 'intangible',
                'description' => 'Tari Linda adalah tarian tradisional masyarakat Muna yang ditarikan oleh gadis-gadis muda. Tarian ini menggambarkan keanggunan dan kelembutan perempuan Muna. Gerakan tarian ini lambat dan mengalir dengan posisi tubuh yang tegak dan tangan yang bergerak lembut mengikuti irama musik pengiring.',
                'historical_significance' => 'Tari Linda awalnya merupakan tarian sakral yang dipersembahkan untuk upacara adat dan ritual keagamaan. Seiring waktu, tarian ini berkembang menjadi tarian untuk menyambut tamu penting dan acara-acara budaya. Tarian ini telah ada sejak masa Kerajaan Muna dan menjadi simbol identitas budaya masyarakat Muna.',
                'location' => 'Seluruh wilayah Muna Barat',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'conservation_status' => 'fair',
                'recognition_status' => 'national',
                'practices_description' => 'Tari Linda ditarikan oleh 4-8 gadis dengan pakaian adat Muna berupa baju bodo dan sarung tenun khas Muna. Musik pengiring terdiri dari gong, gendang, dan alat musik tiup tradisional. Tarian dimulai dengan gerakan lambat dan semakin intens pada bagian tengah, kemudian kembali melambat pada bagian akhir. Penari membentuk formasi lingkaran atau baris sejajar. Tarian biasanya berlangsung sekitar 10-15 menit.',
                'custodian' => 'Sanggar Seni Anawula dan para seniman tradisional Muna',
                'visitor_info' => 'Tari Linda sering ditampilkan pada acara adat, festival budaya, dan upacara penyambutan tamu penting. Wisatawan dapat menyaksikan tarian ini di sanggar seni atau memanggil penari untuk pertunjukan khusus.',
                'is_endangered' => false,
                'allows_visits' => true,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'name' => 'Kaago-ago (Ritual Panen)',
                'type' => 'intangible',
                'description' => 'Kaago-ago adalah ritual adat yang dilakukan masyarakat Muna sebelum dan setelah panen. Ritual ini bertujuan untuk mengucap syukur kepada Tuhan atas hasil panen dan meminta kesuburan untuk musim tanam berikutnya. Ritual ini melibatkan berbagai prosesi termasuk persembahan hasil panen, tarian, nyanyian, dan doa-doa tradisional.',
                'historical_significance' => 'Ritual Kaago-ago telah dilaksanakan selama berabad-abad dan mencerminkan hubungan spiritual yang erat antara masyarakat Muna dengan alam. Ritual ini juga menunjukkan sistem kepercayaan asli masyarakat Muna yang telah berakulturasi dengan ajaran Islam.',
                'location' => 'Area pertanian di Kecamatan Kusambi',
                'district_id' => $districts->where('name', 'Kusambi')->first()->id ?? 5,
                'conservation_status' => 'poor',
                'recognition_status' => 'regional',
                'practices_description' => 'Ritual dipimpin oleh pemimpin adat (parabela) dan dukun (sando). Prosesi dimulai dengan pembacaan mantra dan doa di rumah adat, dilanjutkan dengan arak-arakan ke ladang. Di ladang, dilakukan persembahan berupa nasi, telur, sirih pinang, dan hasil panen pertama kepada roh leluhur dan penguasa tanah. Setelah itu, dilakukan tarian dan nyanyian tradisional yang diikuti seluruh warga desa. Ritual diakhiri dengan makan bersama dari hasil panen yang telah didoakan. Ritual lengkap berlangsung selama satu hari penuh.',
                'custodian' => 'Para pemimpin adat dan dukun desa di Muna Barat',
                'visitor_info' => 'Ritual biasanya dilaksanakan pada awal dan akhir musim panen (sekitar Maret dan September). Pengunjung diperbolehkan menyaksikan dengan izin pemimpin adat dan harus mengikuti etika yang ditentukan.',
                'is_endangered' => true,
                'allows_visits' => true,
                'is_featured' => false,
                'status' => true,
            ],
            [
                'name' => 'Kantola (Tradisi Berpantun)',
                'type' => 'intangible',
                'description' => 'Kantola adalah tradisi berpantun atau berbalas pantun yang dilakukan oleh masyarakat Muna. Pantun-pantun ini biasanya berisi nasihat, sindiran halus, ungkapan cinta, atau kisah-kisah tradisional. Kantola biasanya dilakukan pada malam hari setelah panen atau pada acara adat tertentu.',
                'historical_significance' => 'Tradisi Kantola merupakan bentuk komunikasi sosial dan hiburan tradisional masyarakat Muna sejak masa pra-Islam. Tradisi ini juga berfungsi sebagai media untuk menyampaikan nilai-nilai moral, kritik sosial, dan menjaga harmoni masyarakat melalui sindiran-sindiran halus.',
                'location' => 'Seluruh wilayah Muna Barat, terutama di Kecamatan Tiworo Tengah',
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id ?? 6,
                'conservation_status' => 'critical',
                'recognition_status' => 'local',
                'practices_description' => 'Kantola dilakukan oleh dua kelompok yang saling berhadapan, biasanya laki-laki dan perempuan atau dua kampung yang berbeda. Setiap peserta secara bergantian menyampaikan pantun dengan irama khas yang diiringi oleh tepukan tangan atau alat musik sederhana. Pantun terdiri dari 4-8 baris dengan aturan rima yang ketat. Tema pantun bisa beragam dari romantis, jenaka, hingga filosofis. Acara Kantola bisa berlangsung dari senja hingga dini hari.',
                'custodian' => 'Para tetua adat dan seniman tradisional Muna',
                'visitor_info' => 'Pertunjukan Kantola biasanya diadakan pada perayaan panen, acara adat, atau festival budaya. Wisatawan dapat berpartisipasi dengan menyiapkan pantun sederhana dalam bahasa Indonesia.',
                'is_endangered' => true,
                'allows_visits' => true,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'name' => 'Kasambu (Upacara Kelahiran)',
                'type' => 'intangible',
                'description' => 'Kasambu adalah upacara adat yang dilakukan untuk menyambut kelahiran bayi dalam masyarakat Muna. Upacara ini melibatkan serangkaian ritual untuk melindungi bayi dari roh jahat, memberikan berkah, dan memperkenalkan bayi kepada masyarakat dan alam sekitar.',
                'historical_significance' => 'Tradisi Kasambu menunjukkan pentingnya kelahiran dalam budaya Muna dan merefleksikan kepercayaan tentang hubungan antara manusia, alam, dan dunia spiritual. Ritual ini juga mencerminkan nilai-nilai keluarga dan komunitas dalam masyarakat tradisional Muna.',
                'location' => 'Kecamatan Wadaga',
                'district_id' => $districts->where('name', 'Wadaga')->first()->id ?? 7,
                'conservation_status' => 'fair',
                'recognition_status' => 'local',
                'practices_description' => 'Upacara dilakukan pada hari ketujuh setelah kelahiran, dipimpin oleh dukun beranak (sandro) dan dihadiri keluarga besar serta tetangga. Ritual dimulai dengan memandikan bayi menggunakan air yang telah dicampur dengan 7 jenis bunga dan rempah. Kemudian dilakukan pemotongan rambut bayi yang pertama, dengan rambut dikumpulkan dalam kelapa muda. Orang tua bayi memberikan sedekah berupa beras, kain, dan uang kepada dukun dan fakir miskin. Dilanjutkan dengan pembacaan doa oleh imam desa dan diakhiri dengan makan bersama. Nama bayi juga diumumkan secara resmi dalam upacara ini.',
                'custodian' => 'Dukun beranak tradisional dan pemuka agama Muna',
                'visitor_info' => 'Upacara Kasambu adalah acara keluarga, namun wisatawan dapat menyaksikan jika mendapat undangan dari keluarga yang bersangkutan. Pengunjung diharapkan membawa hadiah kecil untuk bayi.',
                'is_endangered' => true,
                'allows_visits' => true,
                'is_featured' => false,
                'status' => true,
            ],
            [
                'name' => 'Kaseseha (Tradisi Menenun)',
                'type' => 'intangible',
                'description' => 'Kaseseha adalah tradisi menenun kain yang dilakukan secara turun-temurun oleh perempuan Muna. Kain tenun Muna memiliki motif-motif khas yang merefleksikan alam, kehidupan sehari-hari, dan falsafah hidup masyarakat Muna. Proses menenun dilakukan dengan alat tenun tradisional dan menggunakan teknik yang telah diwariskan selama berabad-abad.',
                'historical_significance' => 'Tradisi menenun di Muna telah ada sejak abad ke-14 dan menjadi bagian penting dalam kehidupan perempuan Muna. Kain tenun tidak hanya berfungsi sebagai pakaian tetapi juga sebagai simbol status sosial, identitas budaya, dan digunakan dalam berbagai ritual adat.',
                'location' => 'Desa Wakobalu, Kecamatan Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id ?? 1,
                'conservation_status' => 'good',
                'recognition_status' => 'national',
                'practices_description' => 'Proses menenun dimulai dari memintal benang dari kapas atau serat alam lainnya. Kemudian benang diwarnai menggunakan pewarna alami dari tumbuhan seperti mengkudu untuk warna merah, daun tarum untuk biru, dan kunyit untuk kuning. Setelah kering, benang disusun di alat tenun tradisional (bhira) dan ditenun dengan teknik ikat atau songket. Motif-motif yang umum adalah kalambe (bunga), wunalangka (gelombang laut), dan kansibhe (burung). Proses pembuatan satu kain tenun bisa memakan waktu berminggu-minggu hingga berbulan-bulan tergantung kerumitan motif.',
                'custodian' => 'Para penenun tradisional di Desa Wakobalu',
                'visitor_info' => 'Pengunjung dapat mengunjungi desa penenun untuk melihat proses pembuatan kain tenun. Tersedia juga workshop singkat untuk belajar dasar-dasar menenun. Kain tenun dapat dibeli langsung dari pengrajin.',
                'is_endangered' => false,
                'allows_visits' => true,
                'is_featured' => true,
                'status' => true,
            ],
        ];

        // Gabungkan kedua array warisan budaya
        $culturalHeritages = array_merge($tangibleHeritages, $intangibleHeritages);

        // Insert data warisan budaya
        foreach ($culturalHeritages as $heritage) {
            $heritageModel = CulturalHeritage::create($heritage);

            // Tambahkan dummy image untuk setiap warisan budaya
            $imageName = Str::slug($heritage['name']) . '.jpg';
            Gallery::create([
                'imageable_id' => $heritageModel->id,
                'imageable_type' => CulturalHeritage::class,
                'file_path' => 'dummy/cultural_heritages/' . $imageName,
                'caption' => 'Foto ' . $heritage['name'],
                'is_featured' => true,
                'order' => 0,
            ]);
        }

        $this->command->info('Seeder untuk Cultural Heritage berhasil dijalankan!');
    }
}
