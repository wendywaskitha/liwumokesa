<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public function getColumns(): array
    {
        return [
            'name' => 'Nama Produk',
            'description' => 'Deskripsi',
            'price' => 'Harga',
            'discounted_price' => 'Harga Diskon',
            'stock' => 'Stok',
            'material' => 'Bahan',
            'size' => 'Ukuran',
            'weight' => 'Berat',
            'dimensions' => 'Dimensi',
            'colors' => 'Warna',
            'is_featured' => 'Produk Unggulan',
            'is_custom_order' => 'Pesanan Khusus',
            'production_time' => 'Waktu Produksi',
            'status' => 'Status',
        ];
    }

    public function resolveRecord(): ?Product
    {
        // Buat record baru untuk setiap baris impor
        return new Product();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import produk berhasil dengan ' . number_format($import->successful_rows) . ' baris berhasil diproses.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diproses.';
        }

        return $body;
    }

    public function getFormSchema(): array
    {
        return [
            // Tidak ada komponen form tambahan yang diperlukan
        ];
    }

    // Menangani setiap baris data impor
    public function map(array $row): array
    {
        // Map nilai boolean
        $is_featured = is_string($row['is_featured'])
            ? strtolower($row['is_featured']) === 'ya'
            : (bool) $row['is_featured'];

        $is_custom_order = is_string($row['is_custom_order'])
            ? strtolower($row['is_custom_order']) === 'ya'
            : (bool) $row['is_custom_order'];

        $status = is_string($row['status'])
            ? strtolower($row['status']) === 'aktif'
            : (bool) $row['status'];

        return [
            'creative_economy_id' => $this->options['creativeEconomyId'],
            'name' => $row['name'],
            'slug' => Str::slug($row['name']),
            'description' => $row['description'],
            'price' => $row['price'],
            'discounted_price' => !empty($row['discounted_price']) ? $row['discounted_price'] : null,
            'stock' => $row['stock'] ?? 0,
            'material' => $row['material'],
            'size' => $row['size'],
            'weight' => $row['weight'],
            'dimensions' => $row['dimensions'],
            'colors' => $row['colors'],
            'is_featured' => $is_featured,
            'is_custom_order' => $is_custom_order,
            'production_time' => $row['production_time'],
            'status' => $status,
        ];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'material' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'colors' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_custom_order' => ['nullable', 'boolean'],
            'production_time' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    // Contoh data untuk template
    public static function getExampleData(): array
    {
        return [
            [
                'name' => 'Tas Anyaman Bambu',
                'description' => 'Tas anyaman bambu khas Muna Barat dengan motif tradisional',
                'price' => 150000,
                'discounted_price' => 125000,
                'stock' => 10,
                'material' => 'Bambu, Rotan',
                'size' => '30cm x 25cm x 10cm',
                'weight' => 500,
                'dimensions' => '30x25x10',
                'colors' => 'Coklat, Hitam',
                'is_featured' => 'Ya',
                'is_custom_order' => 'Tidak',
                'production_time' => '',
                'status' => 'Aktif'
            ],
            [
                'name' => 'Kain Tenun Motif Kalambe',
                'description' => 'Kain tenun dengan motif tradisional',
                'price' => 350000,
                'discounted_price' => '',
                'stock' => 5,
                'material' => 'Benang katun',
                'size' => '200cm x 120cm',
                'weight' => 300,
                'dimensions' => '200x120',
                'colors' => 'Merah, Biru',
                'is_featured' => 'Ya',
                'is_custom_order' => 'Ya',
                'production_time' => 14,
                'status' => 'Aktif'
            ],
        ];
    }
}
