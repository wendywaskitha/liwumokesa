<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CreativeEconomy;
use App\Models\District;
use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreativeEconomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data products yang terkait dengan creative_economies terlebih dahulu
        // untuk menghindari constraint violation
        DB::table('products')->where('creative_economy_id', '>', 0)->delete();

        // Hapus galeri yang terkait dengan creative_economies
        DB::table('galleries')
            ->where('imageable_type', CreativeEconomy::class)
            ->delete();

        // Hapus data creative_economies yang ada
        DB::table('creative_economies')->delete();

        // Alternatif lain jika ingin menggunakan truncate (lebih cepat)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // DB::table('creative_economies')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('Existing creative economies data deleted successfully.');

        // Dapatkan semua kecamatan untuk referensi
        $districts = District::all();

        // Dapatkan kategori ekonomi kreatif untuk referensi
        $categoryKerajinan = Category::where('name', 'Kerajinan Tangan')->where('type', 'ekonomi-kreatif')->first();
        if (!$categoryKerajinan) {
            $categoryKerajinan = Category::create([
                'name' => 'Kerajinan Tangan',
                'slug' => 'kerajinan-tangan',
                'type' => 'ekonomi-kreatif',
                'description' => 'Produk kerajinan tangan khas Muna Barat'
            ]);
        }

        $categoryTenun = Category::where('name', 'Tenun')->where('type', 'ekonomi-kreatif')->first();
        if (!$categoryTenun) {
            $categoryTenun = Category::create([
                'name' => 'Tenun',
                'slug' => 'tenun',
                'type' => 'ekonomi-kreatif',
                'description' => 'Produk tenun tradisional khas Muna Barat'
            ]);
        }

        $categoryKuliner = Category::where('name', 'Kuliner')->where('type', 'ekonomi-kreatif')->first();
        if (!$categoryKuliner) {
            $categoryKuliner = Category::create([
                'name' => 'Kuliner',
                'slug' => 'kuliner',
                'type' => 'ekonomi-kreatif',
                'description' => 'Produk kuliner olahan khas Muna Barat'
            ]);
        }

        $categoryFashion = Category::where('name', 'Fashion')->where('type', 'ekonomi-kreatif')->first();
        if (!$categoryFashion) {
            $categoryFashion = Category::create([
                'name' => 'Fashion',
                'slug' => 'fashion',
                'type' => 'ekonomi-kreatif',
                'description' => 'Produk fashion khas Muna Barat'
            ]);
        }

        // Data ekonomi kreatif Muna Barat
        $creativeEconomies = [
            [
                'name' => 'Sanggar Tenun Kamba',
                'slug' => 'sanggar-tenun-kamba',
                'category_id' => $categoryTenun->id,
                'description' => 'Sanggar Tenun Kamba adalah pusat kerajinan tenun tradisional yang melestarikan motif dan teknik tenun khas Muna. Didirikan oleh Ibu Wa Ode Sarina pada tahun 1998, sanggar ini telah menjadi tempat belajar para pengrajin tenun muda dan pusat produksi kain tenun berkualitas tinggi. Produk unggulan dari sanggar ini adalah kain tenun dengan motif "kalambe", "bhia-bhia", dan "kowundu" yang memiliki makna filosofis mendalam dalam budaya Muna. Setiap kain dibuat dengan teliti dan membutuhkan waktu berhari-hari hingga berbulan-bulan tergantung tingkat kerumitan motif. Pengunjung dapat melihat langsung proses menenun dan berpartisipasi dalam workshop singkat untuk belajar dasar-dasar tenun tradisional.',
                'short_description' => 'Pusat kerajinan tenun tradisional yang melestarikan motif dan teknik tenun khas Muna dengan workshop untuk pengunjung.',
                'address' => 'Jl. Pasar Lama No. 15, Desa Lohia, Kec. Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id,
                'latitude' => -4.9542,
                'longitude' => 122.5464,
                'phone_number' => '082187654321',
                'email' => 'tenunkamba@gmail.com',
                'social_media' => '@tenunkamba',
                'business_hours' => '08:00-16:00',
                'owner_name' => 'Wa Ode Sarina',
                'establishment_year' => 1998,
                'employees_count' => 12,
                'products_description' => 'Kain tenun tradisional, selendang, sarung, dan aksesoris berbahan tenun dengan motif khas Muna seperti kalambe, bhia-bhia, dan kowundu.',
                'price_range_start' => 150000,
                'price_range_end' => 5000000,
                'has_workshop' => true,
                'workshop_information' => 'Workshop dasar tenun tradisional (2 jam): Rp 250.000/orang termasuk bahan dan hasil tenun kecil untuk dibawa pulang. Workshop intensif (3 hari): Rp 1.500.000/orang termasuk bahan dan hasil tenun.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => true,
                'is_verified' => true,
                'accepts_credit_card' => false,
                'provides_training' => true,
                'shipping_available' => true
            ],
            [
                'name' => 'Ukiran Kayu La Ode Craft',
                'slug' => 'ukiran-kayu-la-ode-craft',
                'category_id' => $categoryKerajinan->id,
                'description' => 'Ukiran Kayu La Ode Craft adalah bengkel kerajinan ukir kayu yang dikembangkan oleh keluarga La Ode Hamid sejak tahun 1985. Workshop ini menghasilkan berbagai produk ukiran kayu dengan motif-motif khas Muna Barat seperti Kambeano (bunga), Wapulaka (perahu), dan Kabhantapi (alat tenun). Material utama yang digunakan adalah kayu jati lokal dan kayu sono yang diambil secara berkelanjutan dengan izin khusus dari Dinas Kehutanan. Proses pengerjaan dilakukan secara tradisional menggunakan peralatan ukir yang diwariskan secara turun-temurun. Pengunjung dapat melihat proses pembuatan ukiran dan berinteraksi langsung dengan para pengrajin. Untuk pengunjung yang ingin belajar, tersedia kelas singkat pembuatan ukiran sederhana.',
                'short_description' => 'Bengkel kerajinan ukir kayu dengan motif khas Muna Barat yang menawarkan produk berkualitas tinggi dan kelas ukir untuk wisatawan.',
                'address' => 'Desa Wakalambe, Kec. Kusambi',
                'district_id' => $districts->where('name', 'Kusambi')->first()->id,
                'latitude' => -4.9121,
                'longitude' => 122.5376,
                'phone_number' => '081345678901',
                'email' => 'laodecraft@gmail.com',
                'website' => 'laodecraft.com',
                'social_media' => '@laodecraft',
                'business_hours' => '09:00-17:00',
                'owner_name' => 'La Ode Hamid',
                'establishment_year' => 1985,
                'employees_count' => 8,
                'products_description' => 'Patung, panel ukiran, perabotan ukir, souvenir ukir kayu, replika rumah adat dan perahu tradisional Muna.',
                'price_range_start' => 50000,
                'price_range_end' => 10000000,
                'has_workshop' => true,
                'workshop_information' => 'Workshop pengenalan ukir kayu (3 jam): Rp 300.000/orang termasuk material dan alat. Hasil ukiran dapat dibawa pulang.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => true,
                'is_verified' => true,
                'accepts_credit_card' => true,
                'provides_training' => true,
                'shipping_available' => true
            ],
            [
                'name' => 'Olahan Laut Muna Sejahtera',
                'slug' => 'olahan-laut-muna-sejahtera',
                'category_id' => $categoryKuliner->id,
                'description' => 'Olahan Laut Muna Sejahtera adalah kelompok usaha pengolahan hasil laut yang dikelola oleh Koperasi Nelayan Muna Barat. Berdiri sejak 2010, usaha ini memproduksi berbagai makanan olahan dari hasil laut seperti ikan kering, abon ikan tuna, kerupuk kulit ikan, terasi udang, dan sambal ikan. Semua produk diolah dengan metode tradisional tanpa pengawet kimia untuk menjaga kualitas dan cita rasa asli. Pengolahan dilakukan di fasilitas produksi yang bersih dan telah mendapatkan sertifikat PIRT dari Dinas Kesehatan. Pengunjung dapat melihat langsung proses pengolahan dan mengikuti sesi demo pembuatan abon atau kerupuk ikan.',
                'short_description' => 'Kelompok usaha pengolahan hasil laut yang memproduksi berbagai makanan olahan tradisional dengan cita rasa khas Muna Barat.',
                'address' => 'Jl. Pantai Pajala No. 28, Kec. Tiworo Tengah',
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id,
                'latitude' => -4.9844,
                'longitude' => 122.5214,
                'phone_number' => '085678901234',
                'email' => 'olahanlautmuna@gmail.com',
                'social_media' => '@olahanlautmuna',
                'business_hours' => '08:00-17:00',
                'owner_name' => 'Koperasi Nelayan Muna Barat',
                'establishment_year' => 2010,
                'employees_count' => 15,
                'products_description' => 'Abon ikan tuna, kerupuk kulit ikan, ikan kering, terasi udang, sambal ikan rica, ikan asap kemasan, stik rumput laut.',
                'price_range_start' => 15000,
                'price_range_end' => 100000,
                'has_workshop' => true,
                'workshop_information' => 'Demo pembuatan abon ikan: Rp 150.000/grup (maksimal 10 orang) termasuk tester produk dan 1 paket oleh-oleh.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => false,
                'is_verified' => true,
                'accepts_credit_card' => false,
                'provides_training' => true,
                'shipping_available' => true
            ],
            [
                'name' => 'Anyaman Bambu Katobengke',
                'slug' => 'anyaman-bambu-katobengke',
                'category_id' => $categoryKerajinan->id,
                'description' => 'Anyaman Bambu Katobengke adalah kelompok pengrajin bambu yang berasal dari Desa Katobengke. Kelompok ini mempertahankan teknik anyaman tradisional yang telah diwariskan selama berabad-abad. Produk anyaman mereka meliputi berbagai peralatan rumah tangga dan hiasan seperti bakul, nampan, topi, tikar, dan hiasan dinding. Material bambu diambil dari kebun bambu milik desa yang dikelola secara lestari. Setiap produk dibuat dengan teknik khusus yang membutuhkan ketelitian dan keahlian tinggi. Pengunjung dapat melihat proses anyaman dari awal hingga akhir dan mencoba keterampilan dasar menganyam bambu di bawah bimbingan pengrajin berpengalaman.',
                'short_description' => 'Kelompok pengrajin yang mempertahankan teknik anyaman bambu tradisional untuk menghasilkan berbagai produk rumah tangga dan dekoratif.',
                'address' => 'Desa Katobengke, Kec. Lawa',
                'district_id' => $districts->where('name', 'Lawa')->first()->id,
                'latitude' => -4.9431,
                'longitude' => 122.5377,
                'phone_number' => '082390123456',
                'email' => null,
                'social_media' => null,
                'business_hours' => '08:00-16:00',
                'owner_name' => 'Kelompok Pengrajin Desa Katobengke',
                'establishment_year' => 1995,
                'employees_count' => 10,
                'products_description' => 'Bakul, nampan, topi, tikar, hiasan dinding, keranjang, kipas tangan, dan souvenir anyaman mini.',
                'price_range_start' => 15000,
                'price_range_end' => 500000,
                'has_workshop' => true,
                'workshop_information' => 'Workshop dasar anyaman bambu (2 jam): Rp 100.000/orang termasuk material dan hasil anyaman untuk dibawa pulang.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => false,
                'is_verified' => false,
                'accepts_credit_card' => false,
                'provides_training' => true,
                'shipping_available' => false
            ],
            [
                'name' => 'Batik Muna "Bhia-Bhia"',
                'slug' => 'batik-muna-bhia-bhia',
                'category_id' => $categoryFashion->id,
                'description' => 'Batik Muna "Bhia-Bhia" adalah usaha batik yang mengembangkan motif-motif khas Muna yang dipadukan dengan teknik batik. Didirikan oleh Ibu Wa Ode Nurlia pada tahun 2012, usaha ini bertujuan untuk mengembangkan identitas batik khas Muna Barat. Nama "Bhia-Bhia" diambil dari motif tradisional yang berarti "bunga" dalam bahasa Muna. Selain motif tersebut, batik ini juga mengembangkan motif-motif yang terinspirasi dari artefak dan kebudayaan Muna seperti Wale (rumah adat), Wapulaka (perahu), dan Kambeano (tumbuhan). Proses pembuatan dilakukan dengan teknik batik tulis dan batik cap, menggunakan pewarna alami dari tanaman lokal seperti mengkudu, kunyit, dan daun jati.',
                'short_description' => 'Pengembangan batik dengan motif-motif khas Muna yang menggunakan pewarna alami dan teknik batik tulis tradisional.',
                'address' => 'Jl. Raya Sawerigadi No. 45, Kec. Sawerigadi',
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id,
                'latitude' => -4.9759,
                'longitude' => 122.4932,
                'phone_number' => '081234567890',
                'email' => 'batikmuna@gmail.com',
                'website' => 'batikbhiabhia.com',
                'social_media' => '@batikbhiabhia',
                'business_hours' => '09:00-17:00',
                'owner_name' => 'Wa Ode Nurlia',
                'establishment_year' => 2012,
                'employees_count' => 8,
                'products_description' => 'Kain batik, baju batik, selendang, tas batik, dan aksesoris dengan motif khas Muna Barat.',
                'price_range_start' => 150000,
                'price_range_end' => 3000000,
                'has_workshop' => true,
                'workshop_information' => 'Workshop membuat batik cap (3 jam): Rp 350.000/orang termasuk material dan kain batik buatan sendiri ukuran 50x50cm.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => true,
                'is_verified' => true,
                'accepts_credit_card' => true,
                'provides_training' => true,
                'shipping_available' => true
            ],
            [
                'name' => 'Perhiasan Kerang Wa Ina',
                'slug' => 'perhiasan-kerang-wa-ina',
                'category_id' => $categoryKerajinan->id,
                'description' => 'Perhiasan Kerang Wa Ina adalah usaha kerajinan yang mengolah kerang, mutiara, dan bahan laut lainnya menjadi perhiasan dan aksesoris yang indah. Didirikan oleh Ibu Wa Ina, seorang ibu rumah tangga dari keluarga nelayan, usaha ini awalnya merupakan kegiatan sampingan yang kemudian berkembang pesat. Bahan baku didapatkan dari hasil laut sekitar pesisir Muna Barat, dengan memperhatikan keberlanjutan lingkungan. Setiap perhiasan dibuat dengan tangan, dirancang secara detail untuk menghasilkan aksesoris yang unik dan berkualitas. Produk-produk Wa Ina telah dipasarkan hingga ke luar Sulawesi dan menjadi buah tangan favorit para wisatawan.',
                'short_description' => 'Usaha kerajinan perhiasan dan aksesoris dari kerang dan bahan laut lainnya dengan desain unik khas pesisir Muna Barat.',
                'address' => 'Desa Tasipi, Kec. Tiworo Utara',
                'district_id' => $districts->where('name', 'Tiworo Utara')->first()->id,
                'latitude' => -4.9622,
                'longitude' => 122.5533,
                'phone_number' => '085712345678',
                'email' => 'kerangwaina@gmail.com',
                'social_media' => '@kerangwaina',
                'business_hours' => '08:00-17:00',
                'owner_name' => 'Wa Ina',
                'establishment_year' => 2008,
                'employees_count' => 5,
                'products_description' => 'Kalung, gelang, anting, bros, hiasan dinding, dan aksesoris rumah dari kerang, mutiara, batu laut, dan karang.',
                'price_range_start' => 25000,
                'price_range_end' => 750000,
                'has_workshop' => true,
                'workshop_information' => 'Workshop pembuatan perhiasan kerang sederhana (2 jam): Rp 200.000/orang termasuk material dan 1 set perhiasan (kalung dan gelang) untuk dibawa pulang.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => false,
                'is_verified' => true,
                'accepts_credit_card' => false,
                'provides_training' => true,
                'shipping_available' => true
            ],
            [
                'name' => 'Gula Aren Rahampu',
                'slug' => 'gula-aren-rahampu',
                'category_id' => $categoryKuliner->id,
                'description' => 'Gula Aren Rahampu adalah usaha produksi gula aren tradisional yang terletak di dataran tinggi Rahampu. Produksi gula aren ini telah berlangsung secara turun-temurun sejak puluhan tahun lalu, dengan teknik tradisional yang masih dipertahankan hingga kini. Proses pembuatan dimulai dari penyadapan nira pohon aren yang kemudian diolah dengan cara direbus dalam kuali besar hingga mengental dan dicetak dalam tempurung kelapa. Selain gula aren cetak tradisional, usaha ini juga mengembangkan produk turunan seperti gula semut, gula cair, dan manisan berbahan dasar gula aren. Pengunjung dapat melihat langsung proses penyadapan nira di kebun aren dan proses pengolahan di rumah produksi.',
                'short_description' => 'Usaha produksi gula aren tradisional dengan teknik turun-temurun yang menawarkan berbagai produk olahan dari nira pohon aren.',
                'address' => 'Desa Rahampu, Kec. Wadaga',
                'district_id' => $districts->where('name', 'Wadaga')->first()->id,
                'latitude' => -4.9286,
                'longitude' => 122.5731,
                'phone_number' => '082156789012',
                'email' => null,
                'social_media' => null,
                'business_hours' => '07:00-16:00',
                'owner_name' => 'Kelompok Tani Rahampu',
                'establishment_year' => 2005,
                'employees_count' => 7,
                'products_description' => 'Gula aren cetak tradisional, gula semut, gula cair, manisan gula aren, kue-kue tradisional berbahan gula aren.',
                'price_range_start' => 10000,
                'price_range_end' => 50000,
                'has_workshop' => true,
                'workshop_information' => 'Demonstrasi dan praktek membuat gula aren (3 jam): Rp 100.000/orang termasuk paket oleh-oleh gula aren.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => false,
                'is_verified' => false,
                'accepts_credit_card' => false,
                'provides_training' => false,
                'shipping_available' => true
            ],
            [
                'name' => 'Alat Musik Tradisional La Tongka',
                'slug' => 'alat-musik-tradisional-la-tongka',
                'category_id' => $categoryKerajinan->id,
                'description' => 'Alat Musik Tradisional La Tongka adalah bengkel pembuatan alat musik tradisional Muna yang didirikan oleh La Tongka, seorang seniman dan ahli musik tradisional. Bengkel ini memproduksi berbagai alat musik tradisional seperti Mbololo (alat tiup dari bambu), Gambusu (sejenis gitar tradisional), Linda (sejenis harpa mulut), dan Karinta (sejenis sasando). Material yang digunakan berasal dari bahan-bahan lokal seperti bambu, kayu jati, rotan, dan kulit hewan. Setiap alat musik dibuat dengan ketelitian tinggi oleh pengrajin yang sudah berpengalaman puluhan tahun, sehingga menghasilkan alat musik dengan kualitas suara yang baik. Pengunjung dapat melihat proses pembuatan alat musik dan mencoba memainkannya dengan bimbingan.',
                'short_description' => 'Bengkel pembuatan alat musik tradisional Muna yang melestarikan warisan budaya musik lokal dengan kualitas suara yang baik.',
                'address' => 'Desa Wakasole, Kec. Sawerigadi',
                'district_id' => $districts->where('name', 'Sawerigadi')->first()->id,
                'latitude' => -4.9477,
                'longitude' => 122.4830,
                'phone_number' => '085678901234',
                'email' => 'musiklatongka@gmail.com',
                'social_media' => '@musiklatongka',
                'business_hours' => '09:00-16:00',
                'owner_name' => 'La Tongka',
                'establishment_year' => 2001,
                'employees_count' => 4,
                'products_description' => 'Mbololo (alat tiup dari bambu), Gambusu (gitar tradisional), Linda (harpa mulut), Karinta (sejenis sasando), dan alat perkusi tradisional.',
                'price_range_start' => 75000,
                'price_range_end' => 2000000,
                'has_workshop' => true,
                'workshop_information' => 'Workshop pengenalan dan cara memainkan alat musik tradisional (2 jam): Rp 200.000/orang termasuk alat musik miniatur untuk dibawa pulang.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => true,
                'is_verified' => true,
                'accepts_credit_card' => false,
                'provides_training' => true,
                'shipping_available' => true
            ],
            [
                'name' => 'Rajutan Katupat Ibu Farida',
                'slug' => 'rajutan-katupat-ibu-farida',
                'category_id' => $categoryKerajinan->id,
                'description' => 'Rajutan Katupat Ibu Farida adalah usaha rumahan yang menghasilkan berbagai produk rajutan dan anyaman dari bahan daun pandan dan lontar. Usaha ini dipelopori oleh Ibu Farida yang telah menekuni kerajinan rajut dan anyam sejak usia muda. Produk utama yang dihasilkan adalah katupat dengan berbagai bentuk dan ukuran yang biasanya digunakan saat perayaan Idul Fitri. Selain itu, usaha ini juga menghasilkan tikar, tas, topi, dan berbagai souvenir dari daun pandan dan lontar. Semua bahan baku diambil dari tanaman yang ditanam sendiri di pekarangan rumah dan sekitar desa. Pengunjung dapat melihat proses pengolahan bahan baku, pewarnaan, hingga proses merajut dan menganyam.',
                'short_description' => 'Usaha rumahan yang menghasilkan berbagai produk rajutan dan anyaman dari daun pandan dan lontar dengan teknik tradisional.',
                'address' => 'Desa Tiworo Lama, Kec. Tiworo Tengah',
                'district_id' => $districts->where('name', 'Tiworo Tengah')->first()->id,
                'latitude' => -4.9764,
                'longitude' => 122.5233,
                'phone_number' => '081234987654',
                'email' => null,
                'social_media' => null,
                'business_hours' => '08:00-17:00',
                'owner_name' => 'Ibu Farida',
                'establishment_year' => 2007,
                'employees_count' => 3,
                'products_description' => 'Katupat berbagai bentuk, tikar, tas, topi, dan aneka souvenir rajutan dari daun pandan dan lontar.',
                'price_range_start' => 5000,
                'price_range_end' => 200000,
                'has_workshop' => true,
                'workshop_information' => 'Workshop membuat katupat dan anyaman sederhana (1 jam): Rp 50.000/orang termasuk bahan dan hasil kerajinan untuk dibawa pulang.',
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => false,
                'is_verified' => false,
                'accepts_credit_card' => false,
                'provides_training' => true,
                'shipping_available' => false
            ],
            [
                'name' => 'Madu Hutan Tiworo',
                'slug' => 'madu-hutan-tiworo',
                'category_id' => $categoryKuliner->id,
                'description' => 'Madu Hutan Tiworo adalah usaha yang memproduksi dan memasarkan madu hutan murni yang diambil dari kawasan hutan lindung Tiworo. Usaha ini dikelola oleh kelompok pemuda desa yang dilatih oleh Dinas Kehutanan untuk menjadi pemburu madu yang terampil dan memperhatikan keberlanjutan. Pengambilan madu dilakukan dengan metode tradisional yang ramah lingkungan, tanpa merusak sarang lebah dan pohon. Madu yang dihasilkan memiliki cita rasa khas flora Muna dengan tingkat keasaman yang rendah dan aroma rempah yang menonjol. Selain madu murni, usaha ini juga mengembangkan produk turunan seperti lilin lebah, propolis, dan minuman kesehatan berbahan madu. Pengunjung dapat ikut dalam tur edukatif ke lokasi pengambilan madu dan melihat proses pengolahan.',
                'short_description' => 'Usaha produksi dan pemasaran madu hutan murni dari kawasan hutan lindung Tiworo dengan metode pengambilan ramah lingkungan.',
                'address' => 'Desa Tiworo Utara, Kec. Tiworo Kepulauan',
                'district_id' => $districts->where('name', 'Tiworo Kepulauan')->first()->id,
                'latitude' => -5.0122,
                'longitude' => 122.4875,
                'phone_number' => '082345678901',
                'email' => 'maduhutantiworo@gmail.com',
                'website' => 'madutiworo.com',
                'social_media' => '@madutiworo',
                'business_hours' => '08:00-16:00',
                'owner_name' => 'Kelompok Pemuda Tiworo',
                'establishment_year' => 2015,
                'employees_count' => 6,
                'products_description' => 'Madu hutan murni, propolis, lilin lebah, sabun madu, minuman kesehatan berbahan madu, dan permen madu.',
                'price_range_start' => 50000,
                'price_range_end' => 350000,
                'has_workshop' => false,
                'workshop_information' => null,
                'has_direct_selling' => true,
                'status' => true,
                'is_featured' => true,
                'is_verified' => true,
                'accepts_credit_card' => true,
                'provides_training' => false,
                'shipping_available' => true
            ],
        ];

        $addedCount = 0;
        $errorCount = 0;

        // Simpan data ekonomi kreatif ke database
        foreach ($creativeEconomies as $creativeEconomyData) {
            try {
                $this->command->info("Adding creative economy: {$creativeEconomyData['name']}");

                $creativeEconomy = CreativeEconomy::create($creativeEconomyData);

                // Contoh menambahkan gambar dummy untuk setiap ekonomi kreatif
                $imageName = strtolower(str_replace(' ', '_', $creativeEconomy->name)) . '.jpg';

                Gallery::create([
                    'imageable_id' => $creativeEconomy->id,
                    'imageable_type' => CreativeEconomy::class,
                    'file_path' => 'dummy/creative_economies/' . $imageName, // Pastikan folder ini ada di storage/app/public
                    'caption' => 'Foto ' . $creativeEconomy->name,
                    'is_featured' => true,
                    'order' => 0,
                ]);

                $addedCount++;
                $this->command->info("Successfully added: {$creativeEconomy->name}");
            } catch (\Exception $e) {
                $this->command->error("Error adding {$creativeEconomyData['name']}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->command->info("CreativeEconomy seeder berhasil dijalankan. $addedCount ekonomi kreatif telah ditambahkan, dengan $errorCount error.");
    }
}
