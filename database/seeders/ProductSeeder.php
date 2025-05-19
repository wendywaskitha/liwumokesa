<?php

namespace Database\Seeders;

use App\Models\CreativeEconomy;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang sudah ada
        DB::table('products')->truncate();

        // Periksa apakah ada data CreativeEconomy
        $creativeEconomies = CreativeEconomy::all();

        if ($creativeEconomies->isEmpty()) {
            $this->command->info('No CreativeEconomy records found. Please run CreativeEconomySeeder first.');
            return;
        }

        // Kumpulan produk per CreativeEconomy
        $productsByCreativeEconomy = [
            'Sanggar Tenun Kamba' => [
                [
                    'name' => 'Selendang Tenun Motif Kalambe',
                    'description' => 'Selendang tenun tradisional dengan motif Kalambe yang melambangkan keindahan bunga dalam budaya Muna. Dibuat dengan teknik tenun tradisional yang telah diwariskan selama berabad-abad. Menggunakan benang katun berkualitas tinggi dengan pewarna alami. Ukuran selendang 50cm x 200cm, cocok untuk digunakan dalam berbagai kesempatan formal maupun casual. Setiap selendang dikerjakan dengan teliti selama 3-4 hari oleh pengrajin berpengalaman.',
                    'price' => 250000,
                    'stock' => 15,
                    'material' => 'Benang katun, pewarna alami',
                    'size' => '50cm x 200cm',
                    'weight' => '250gr',
                    'colors' => 'Merah, Biru, Hitam',
                    'is_featured' => true,
                    'production_time' => 5,
                ],
                [
                    'name' => 'Sarung Tenun Motif Bhia-Bhia',
                    'description' => 'Sarung tenun tradisional dengan motif Bhia-Bhia yang melambangkan keindahan bunga dalam budaya Muna. Dibuat dengan teknik tenun ikat yang rumit dan membutuhkan ketelitian tinggi. Proses pembuatan memakan waktu 2-3 minggu untuk satu sarung karena kerumitan motif dan teknik pewarnaan yang berlapis. Menggunakan benang sutra asli dengan pewarna alami. Sarung ini cocok untuk acara adat, pernikahan, atau sebagai koleksi pakaian tradisional berkualitas tinggi.',
                    'price' => 1500000,
                    'discounted_price' => 1350000,
                    'stock' => 5,
                    'material' => 'Benang sutra, pewarna alami',
                    'size' => '110cm x 220cm',
                    'weight' => '400gr',
                    'colors' => 'Merah maroon, Biru tua, Hitam',
                    'is_featured' => true,
                    'production_time' => 20,
                ],
                [
                    'name' => 'Kain Tenun Motif Kowundu',
                    'description' => 'Kain tenun dengan motif Kowundu yang memiliki makna filosofis mendalam tentang persatuan dan kekeluargaan dalam budaya Muna. Kain ini dibuat dengan teknik tenun tradisional menggunakan alat tenun bukan mesin (ATBM). Proses pembuatannya memakan waktu hingga satu bulan karena kerumitan motif dan proses pewarnaan alami yang bertahap. Kain ini sering digunakan untuk upacara adat penting atau sebagai hiasan dinding yang bernilai tinggi.',
                    'price' => 3500000,
                    'stock' => 2,
                    'material' => 'Benang sutra premium, pewarna alami',
                    'size' => '120cm x 250cm',
                    'weight' => '500gr',
                    'colors' => 'Hitam, Merah, Kuning emas',
                    'is_featured' => true,
                    'production_time' => 30,
                ],
                [
                    'name' => 'Tas Tenun Etnik',
                    'description' => 'Tas dengan aplikasi kain tenun motif tradisional Muna yang dipadukan dengan bahan kulit sintesis berkualitas. Desain modern namun tetap mempertahankan unsur tradisional membuatnya cocok untuk digunakan sehari-hari maupun acara formal. Terdapat kompartemen utama dengan resleting dan saku tambahan di bagian dalam. Dilengkapi dengan tali panjang yang dapat disesuaikan.',
                    'price' => 350000,
                    'discounted_price' => 300000,
                    'stock' => 8,
                    'material' => 'Kain tenun, kulit sintesis',
                    'size' => '25cm x 20cm x 8cm',
                    'weight' => '350gr',
                    'colors' => 'Hitam-Merah, Coklat-Biru',
                    'is_featured' => false,
                    'production_time' => 7,
                ],
                [
                    'name' => 'Dompet Tenun Kecil',
                    'description' => 'Dompet kecil dengan aplikasi kain tenun motif tradisional yang praktis dan stylish. Cocok untuk menyimpan kartu, uang kertas, dan koin. Dilengkapi dengan resleting YKK berkualitas tinggi dan lapisan dalam yang rapi. Produk ini merupakan hasil kolaborasi antara pengrajin tenun tradisional dan desainer modern.',
                    'price' => 125000,
                    'stock' => 20,
                    'material' => 'Kain tenun, kanvas',
                    'size' => '12cm x 9cm',
                    'weight' => '100gr',
                    'colors' => 'Merah, Biru, Hijau',
                    'is_featured' => false,
                    'production_time' => 3,
                ],
            ],
            'Ukiran Kayu La Ode Craft' => [
                [
                    'name' => 'Patung Ukir Wapulaka',
                    'description' => 'Patung ukiran kayu berbentuk perahu tradisional Wapulaka yang merupakan simbol kehidupan masyarakat pesisir Muna Barat. Dibuat dari kayu jati tua yang diukir dengan detail yang sangat halus menampilkan lengkungan perahu dan ornamen tradisional. Ukiran ini membutuhkan waktu sekitar 3 minggu pengerjaan oleh pengrajin senior. Cocok sebagai elemen dekorasi eksklusif atau cinderamata premium.',
                    'price' => 2500000,
                    'stock' => 3,
                    'material' => 'Kayu jati tua',
                    'size' => '60cm x 15cm x 20cm',
                    'weight' => '5kg',
                    'colors' => 'Natural, Coklat tua',
                    'is_featured' => true,
                    'is_custom_order' => true,
                    'production_time' => 21,
                ],
                [
                    'name' => 'Panel Ukir Motif Kambeano',
                    'description' => 'Panel ukiran kayu dengan motif Kambeano (bunga) yang menampilkan keindahan alam Flora Muna Barat. Dibuat dari kayu sono pilihan dengan tekstur dan serat yang indah. Proses pengerjaan menggunakan teknik ukir timbul tradisional dengan kedalaman ukiran bervariasi menciptakan dimensi yang menarik. Panel ini bisa dipasang di dinding sebagai elemen dekorasi atau partisi ruangan.',
                    'price' => 4500000,
                    'stock' => 1,
                    'material' => 'Kayu sono',
                    'size' => '100cm x 60cm x 5cm',
                    'weight' => '8kg',
                    'colors' => 'Natural',
                    'is_featured' => true,
                    'is_custom_order' => true,
                    'production_time' => 30,
                ],
                [
                    'name' => 'Miniatur Rumah Adat Muna',
                    'description' => 'Miniatur rumah adat Muna yang dibuat dengan detail yang sangat presisi. Menampilkan struktur rumah panggung khas Muna dengan ornamen ukiran tradisional pada tiang dan dinding. Dibuat dari kayu jati muda dengan finishing natural oil untuk mempertahankan keindahan serat kayu. Produk ini merupakan representasi budaya arsitektur tradisional Muna yang telah bertahan selama berabad-abad.',
                    'price' => 1200000,
                    'discounted_price' => 1000000,
                    'stock' => 5,
                    'material' => 'Kayu jati muda',
                    'size' => '30cm x 25cm x 35cm',
                    'weight' => '2kg',
                    'colors' => 'Natural',
                    'is_featured' => false,
                    'production_time' => 14,
                ],
                [
                    'name' => 'Ukiran Topeng Muna',
                    'description' => 'Ukiran topeng tradisional Muna yang menggambarkan karakter dalam cerita rakyat dan tarian tradisional. Dibuat dari kayu pilihan dengan ukiran detail pada setiap fitur wajah. Topeng ini bukan hanya berfungsi sebagai hiasan dinding tetapi juga merepresentasikan kekayaan budaya dan seni Muna Barat. Tersedia dalam beberapa karakter dengan ekspresi yang berbeda.',
                    'price' => 850000,
                    'stock' => 8,
                    'material' => 'Kayu mahoni',
                    'size' => '25cm x 18cm x 10cm',
                    'weight' => '900gr',
                    'colors' => 'Natural, Antik',
                    'is_featured' => true,
                    'production_time' => 10,
                ],
                [
                    'name' => 'Kotak Perhiasan Ukir',
                    'description' => 'Kotak perhiasan dengan ukiran motif tradisional Muna pada seluruh permukaannya. Bagian dalam dilapisi dengan beludru lembut untuk melindungi perhiasan. Dilengkapi dengan kunci kecil untuk keamanan. Kotak ini merupakan perpaduan sempurna antara fungsi praktis dan nilai seni yang tinggi. Cocok sebagai hadiah istimewa atau penyimpan perhiasan berharga.',
                    'price' => 750000,
                    'stock' => 12,
                    'material' => 'Kayu jati, beludru',
                    'size' => '20cm x 15cm x 8cm',
                    'weight' => '1.2kg',
                    'colors' => 'Coklat tua, Hitam',
                    'is_featured' => false,
                    'production_time' => 7,
                ],
            ],
            'Madu Hutan Tiworo' => [
                [
                    'name' => 'Madu Hutan Murni Premium 500ml',
                    'description' => 'Madu hutan murni 100% yang dipanen dari kawasan hutan lindung Tiworo. Madu ini dihasilkan oleh lebah liar yang mengambil nektar dari beragam bunga hutan, menciptakan rasa yang kompleks dengan sedikit sentuhan rempah. Proses pengambilan dilakukan dengan metode tradisional yang ramah lingkungan tanpa merusak habitat lebah. Madu tidak melalui proses pemanasan untuk mempertahankan enzim dan nutrisi alaminya.',
                    'price' => 150000,
                    'stock' => 25,
                    'material' => 'Madu murni',
                    'size' => '500ml',
                    'weight' => '750gr',
                    'colors' => 'Amber',
                    'is_featured' => true,
                    'production_time' => null,
                ],
                [
                    'name' => 'Madu Hutan dengan Sarang 250ml',
                    'description' => 'Madu hutan premium yang dikemas beserta potongan sarang lebah asli. Sarang lebah mengandung propolis, royal jelly, dan bee pollen yang memiliki banyak manfaat kesehatan. Produk ini memberikan pengalaman menikmati madu yang paling autentik. Memiliki rasa yang lebih kompleks dan tekstur yang berbeda dengan adanya potongan sarang yang dapat dikunyah.',
                    'price' => 120000,
                    'stock' => 15,
                    'material' => 'Madu murni, sarang lebah',
                    'size' => '250ml',
                    'weight' => '400gr',
                    'colors' => 'Amber gelap',
                    'is_featured' => true,
                    'production_time' => null,
                ],
                [
                    'name' => 'Propolis Tiworo 60ml',
                    'description' => 'Propolis cair yang diekstrak dari sarang lebah hutan Tiworo. Propolis adalah zat resin yang dikumpulkan lebah dari tunas pohon dan dicampur dengan enzim lebah, memiliki sifat antibakteri, antivirus, dan antioksidan yang kuat. Produk ini dikemas dalam botol kaca gelap untuk melindungi dari sinar UV. Cocok untuk meningkatkan daya tahan tubuh dan mengatasi berbagai masalah kesehatan.',
                    'price' => 175000,
                    'discounted_price' => 150000,
                    'stock' => 10,
                    'material' => 'Ekstrak propolis',
                    'size' => '60ml',
                    'weight' => '150gr',
                    'colors' => 'Coklat tua',
                    'is_featured' => false,
                    'production_time' => null,
                ],
                [
                    'name' => 'Sabun Madu Herbal',
                    'description' => 'Sabun alami yang terbuat dari bahan-bahan organik dengan kandungan madu hutan Tiworo. Diperkaya dengan minyak esensial dan ekstrak herbal lokal yang memberikan efek melembabkan dan menyegarkan kulit. Proses pembuatan menggunakan metode cold process untuk mempertahankan manfaat semua bahan. Sabun tidak mengandung SLS, paraben, atau bahan kimia keras lainnya.',
                    'price' => 45000,
                    'stock' => 50,
                    'material' => 'Madu, minyak kelapa, minyak zaitun, ekstrak herbal',
                    'size' => '100gr',
                    'weight' => '120gr',
                    'colors' => 'Kuning kecoklatan',
                    'is_featured' => false,
                    'production_time' => null,
                ],
                [
                    'name' => 'Paket Gift Box Madu Tiworo',
                    'description' => 'Paket hadiah premium yang berisi produk-produk unggulan Madu Hutan Tiworo. Setiap kotak berisi 1 botol madu hutan murni 250ml, 1 botol propolis 30ml, dan 2 buah sabun madu herbal. Dikemas dalam kotak kayu yang diukir dengan motif tradisional Muna. Pilihan hadiah yang sempurna untuk orang-orang istimewa atau sebagai oleh-oleh premium dari Muna Barat.',
                    'price' => 350000,
                    'stock' => 8,
                    'material' => 'Madu, propolis, sabun herbal, kotak kayu',
                    'size' => '25cm x 25cm x 10cm',
                    'weight' => '1.5kg',
                    'colors' => 'Natural',
                    'is_featured' => true,
                    'production_time' => 1,
                ],
            ],
            'Batik Muna "Bhia-Bhia"' => [
                [
                    'name' => 'Kain Batik Motif Wapulaka',
                    'description' => 'Kain batik tulis dengan motif Wapulaka (perahu tradisional) yang menggambarkan kehidupan maritim masyarakat Muna Barat. Dibuat dengan teknik batik tulis tradisional menggunakan canting dan malam berkualitas tinggi. Pewarnaan menggunakan bahan alami seperti mengkudu untuk warna merah, daun tarum untuk biru, dan kunyit untuk kuning. Setiap lembar kain melewati lebih dari 10 tahap proses yang membutuhkan waktu hingga 3 minggu.',
                    'price' => 1200000,
                    'stock' => 5,
                    'material' => 'Kain katun primisima, pewarna alami',
                    'size' => '200cm x 110cm',
                    'weight' => '400gr',
                    'colors' => 'Indigo, Soga (coklat), Merah mengkudu',
                    'is_featured' => true,
                    'production_time' => 21,
                ],
                [
                    'name' => 'Kemeja Batik Pria Motif Bhia-Bhia',
                    'description' => 'Kemeja batik pria dengan motif Bhia-Bhia (bunga) yang telah dimodernisasi tanpa menghilangkan esensi tradisionalnya. Dibuat dari kain katun yang nyaman dipakai dengan jahitan berkualitas tinggi. Desain kemeja casual yang cocok untuk berbagai kesempatan, baik formal maupun informal. Tersedia dalam beberapa ukuran dan variasi warna.',
                    'price' => 450000,
                    'discounted_price' => 375000,
                    'stock' => 15,
                    'material' => 'Katun',
                    'size' => 'M, L, XL, XXL',
                    'weight' => '250gr',
                    'colors' => 'Biru navy, Coklat soga',
                    'is_featured' => true,
                    'production_time' => 3,
                ],
                [
                    'name' => 'Dress Batik Wanita',
                    'description' => 'Dress batik wanita dengan desain modern yang menggabungkan motif tradisional Muna. Potongan dress A-line yang flattering untuk berbagai bentuk tubuh dengan detail kerah V yang anggun. Dilengkapi dengan ritsleting belakang dan saku samping tersembunyi. Bahan katun yang nyaman dipakai sehari-hari maupun untuk acara khusus.',
                    'price' => 550000,
                    'stock' => 10,
                    'material' => 'Katun',
                    'size' => 'S, M, L, XL',
                    'weight' => '300gr',
                    'colors' => 'Merah bata, Hijau botol',
                    'is_featured' => false,
                    'production_time' => 5,
                ],
                [
                    'name' => 'Scarf Batik Sutra',
                    'description' => 'Scarf batik yang terbuat dari sutra halus dengan motif Muna yang elegan. Pinggiran dijahit rapi dengan teknik hand-rolled creating untuk tampilan yang mewah. Produk ini sangat serbaguna, bisa dipakai sebagai aksesoris leher, penutup kepala, atau bahkan penghias tas. Dikemas dalam kotak eksklusif sehingga cocok untuk hadiah.',
                    'price' => 350000,
                    'stock' => 8,
                    'material' => 'Sutra',
                    'size' => '90cm x 90cm',
                    'weight' => '100gr',
                    'colors' => 'Biru muda, Merah, Hijau',
                    'is_featured' => true,
                    'production_time' => 7,
                ],
                [
                    'name' => 'Tas Tote Batik',
                    'description' => 'Tas tote multifungsi dengan aplikasi kain batik motif Muna yang dipadukan dengan kanvas tebal. Bagian dalam dilapisi dengan kain waterproof dan dilengkapi dengan saku resleting. Tali tas cukup panjang untuk bisa dipakai di bahu. Cocok untuk digunakan sehari-hari, belanja, atau kegiatan santai di akhir pekan.',
                    'price' => 275000,
                    'discounted_price' => 250000,
                    'stock' => 20,
                    'material' => 'Kain batik, kanvas, waterproof lining',
                    'size' => '35cm x 40cm x 12cm',
                    'weight' => '400gr',
                    'colors' => 'Natural-Biru, Natural-Coklat',
                    'is_featured' => false,
                    'production_time' => 3,
                ],
            ],
        ];

        $addedProductsCount = 0;

        // Simpan data produk ke database
        foreach ($productsByCreativeEconomy as $creativeEconomyName => $products) {
            $creativeEconomy = $creativeEconomies->where('name', $creativeEconomyName)->first();

            if (!$creativeEconomy) {
                $this->command->info("Creative Economy '$creativeEconomyName' not found. Skipping its products.");
                continue;
            }

            foreach ($products as $productData) {
                $productData['creative_economy_id'] = $creativeEconomy->id;
                $productData['slug'] = Str::slug($productData['name']);

                try {
                    $product = Product::create($productData);

                    // Buat galeri untuk produk
                    $imageName = strtolower(str_replace(' ', '_', $product->name)) . '.jpg';
                    Gallery::create([
                        'imageable_id' => $product->id,
                        'imageable_type' => Product::class,
                        'file_path' => 'dummy/products/' . $imageName,
                        'caption' => 'Foto ' . $product->name,
                        'is_featured' => true,
                        'order' => 0,
                    ]);

                    $addedProductsCount++;
                } catch (\Exception $e) {
                    $this->command->error("Error adding product '{$productData['name']}': " . $e->getMessage());
                }
            }
        }

        $this->command->info("Product seeder berhasil dijalankan. $addedProductsCount produk telah ditambahkan.");
    }
}
