<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductTemplateController extends Controller
{
    public function downloadTemplate()
    {
        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headings
        $headings = [
            'nama_produk', 'deskripsi', 'harga', 'harga_diskon', 'stok',
            'bahan', 'ukuran', 'berat', 'dimensi', 'warna',
            'produk_unggulan', 'pesanan_khusus', 'waktu_produksi', 'status'
        ];
        $column = 1;
        foreach ($headings as $heading) {
            $cell = $sheet->getCellByColumnAndRow($column, 1);
            $cell->setValue($heading);
            $column++;
        }

        // Set example data
        $exampleData = [
            [
                'Tas Anyaman Bambu', 'Tas anyaman bambu khas Muna Barat dengan motif tradisional', 150000, 125000, 10,
                'Bambu, Rotan', '30cm x 25cm x 10cm', 500, '30x25x10', 'Coklat, Hitam',
                'Ya', 'Tidak', '', 'Aktif'
            ],
            [
                'Kain Tenun Motif Kalambe', 'Kain tenun dengan motif tradisional', 350000, '', 5,
                'Benang katun', '200cm x 120cm', 300, '200x120', 'Merah, Biru',
                'Ya', 'Ya', '14', 'Aktif'
            ],
        ];

        $row = 2;
        foreach ($exampleData as $data) {
            $column = 1;
            foreach ($data as $value) {
                $sheet->getCellByColumnAndRow($column, $row)->setValue($value);
                $column++;
            }
            $row++;
        }

        // Add instructions sheet
        $instructionSheet = $spreadsheet->createSheet();
        $instructionSheet->setTitle('Petunjuk');

        $instructions = [
            ['PETUNJUK PENGISIAN TEMPLATE PRODUK'],
            [''],
            ['1. Kolom dengan tanda (*) wajib diisi.'],
            ['2. Kolom nama_produk*: Nama produk (wajib, maksimal 255 karakter)'],
            ['3. Kolom deskripsi: Deskripsi lengkap produk (opsional)'],
            ['4. Kolom harga*: Harga produk dalam Rupiah tanpa tanda pemisah ribuan (contoh: 150000)'],
            ['5. Kolom harga_diskon: Harga diskon produk jika ada (opsional, harus lebih kecil dari harga normal)'],
            ['6. Kolom stok: Jumlah stok produk (default: 0)'],
            ['7. Kolom bahan: Bahan yang digunakan dalam produk (opsional)'],
            ['8. Kolom ukuran: Ukuran produk (opsional)'],
            ['9. Kolom berat: Berat produk dalam gram (opsional)'],
            ['10. Kolom dimensi: Dimensi produk dalam format PxLxT (opsional)'],
            ['11. Kolom warna: Warna yang tersedia, pisahkan dengan koma (opsional)'],
            ['12. Kolom produk_unggulan: Isi dengan "Ya" atau "Tidak" (default: Tidak)'],
            ['13. Kolom pesanan_khusus: Isi dengan "Ya" atau "Tidak" (default: Tidak)'],
            ['14. Kolom waktu_produksi: Isi dengan jumlah hari yang dibutuhkan jika pesanan khusus (opsional)'],
            ['15. Kolom status: Isi dengan "Aktif" atau "Nonaktif" (default: Aktif)'],
            [''],
            ['Catatan:'],
            ['- Pastikan format isian sesuai dengan ketentuan'],
            ['- Contoh data sudah disediakan di sheet "Data Produk"'],
            ['- Untuk mengimpor, hapus baris contoh dan isi dengan data Anda']
        ];

        $row = 1;
        foreach ($instructions as $instruction) {
            $instructionSheet->setCellValue('A'.$row, $instruction[0]);
            $row++;
        }

        // Format heading
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Auto size columns
        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Rename worksheet
        $sheet->setTitle('Data Produk');

        // Format instruction sheet
        $instructionSheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
        ]);

        $instructionSheet->getColumnDimension('A')->setWidth(100);

        // Create Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'template_produk.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
