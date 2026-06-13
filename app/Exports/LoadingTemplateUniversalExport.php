<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LoadingTemplateUniversalExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new LoadingFormSheet(),
            new MasterDataSheet(),
        ];
    }
}

class LoadingFormSheet implements 
    \Maatwebsite\Excel\Concerns\FromArray,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    public function array(): array
    {
        // Return 30 empty rows for data entry
        return array_fill(0, 30, [
            '', // Nama Produk (will be dropdown)
            '', // Kategori (will be VLOOKUP)
            '', // Kode Produksi
            '', // Best Before
            '', // Jumlah Kemasan
            '', // Jumlah Sampling
            '', // Berat Per Karung/Box
            'ok', // Kondisi Kemasan (default ok)
            '', // Keterangan
            '', // ID Produk (Hidden - will be VLOOKUP)
        ]);
    }

    public function headings(): array
    {
        return [
            'NAMA PRODUK',
            'KATEGORI',
            'KODE PRODUKSI',
            'BEST BEFORE (dd-mmm-yy)',
            'JUMLAH KEMASAN',
            'JUMLAH SAMPLING',
            'BERAT PER KARUNG/BOX',
            'KONDISI KEMASAN (ok/tidak)',
            'KETERANGAN',
            'ID_PRODUK'
        ];
    }

    public function title(): string
    {
        return 'Form Input';
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Add title and instructions
                $sheet->insertNewRowBefore(1, 3);
                
                // Title
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'TEMPLATE LOADING PRODUK - UNIVERSAL');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2E75B5']
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);
                
                // Instructions
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', 'CARA MENGGUNAKAN: 1) Klik dropdown "NAMA PRODUK" dan pilih produk 2) Kategori akan terisi otomatis 3) Isi kolom Kode Produksi, Best Before, Jumlah, dll 4) Simpan dan upload file ini');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFF2CC']
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, 'wrapText' => true]
                ]);
                $sheet->getRowDimension(2)->setRowHeight(40);
                
                // Empty row
                $sheet->getRowDimension(3)->setRowHeight(5);
                
                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(45); // Nama Produk (wider)
                $sheet->getColumnDimension('B')->setWidth(15); // Kategori
                $sheet->getColumnDimension('C')->setWidth(20); // Kode Produksi
                $sheet->getColumnDimension('D')->setWidth(20); // Best Before
                $sheet->getColumnDimension('E')->setWidth(18); // Jumlah Kemasan
                $sheet->getColumnDimension('F')->setWidth(18); // Jumlah Sampling
                $sheet->getColumnDimension('G')->setWidth(22); // Berat
                $sheet->getColumnDimension('H')->setWidth(22); // Kondisi
                $sheet->getColumnDimension('I')->setWidth(30); // Keterangan
                $sheet->getColumnDimension('J')->setWidth(10); // ID (will be hidden)
                
                // Hide column J (ID_PRODUK)
                $sheet->getColumnDimension('J')->setVisible(false);
                
                // Add VLOOKUP formulas for Kategori (Column B)
                for ($row = 5; $row <= 34; $row++) {
                    $sheet->setCellValue("B{$row}", 
                        "=IF(A{$row}=\"\",\"\",IFERROR(VLOOKUP(A{$row},'Master Data'!\$A:\$B,2,FALSE),\"\"))"
                    );
                    
                    // Add VLOOKUP formulas for ID_PRODUK (Hidden Column J)
                    $sheet->setCellValue("J{$row}", 
                        "=IF(A{$row}=\"\",\"\",IFERROR(VLOOKUP(A{$row},'Master Data'!\$A:\$C,3,FALSE),\"\"))"
                    );
                }
                
                // Add Data Validation (Dropdown) for Nama Produk column (A5:A34)
                $validation = $sheet->getDataValidation('A5:A34');
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input Error');
                $validation->setError('Pilih produk dari dropdown');
                $validation->setPromptTitle('Pilih Produk');
                $validation->setPrompt('Klik dropdown untuk memilih produk');
                $validation->setFormula1("'Master Data'!\$A\$2:\$A\$5000"); // Increased range to support more products
                
                // Lock protected columns (Kategori and ID)
                $sheet->getStyle('B5:B34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
                $sheet->getStyle('J5:J34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
                
                // Unlock editable columns
                $sheet->getStyle('A5:A34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
                $sheet->getStyle('C5:I34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
                
                // Freeze panes (freeze first 4 rows and column A)
                $sheet->freezePane('B5');
                
                // Add borders to data area
                $sheet->getStyle('A4:I34')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC']
                        ]
                    ]
                ]);
                // Removed sheet protection from Form Input to avoid issues on tablets/WPS
                // but we keep Master Data sheet protected separately.
            },
        ];
    }
}

class MasterDataSheet implements 
    \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    public function collection()
    {
        // Get ALL products without any filter, ordered by name for better UX
        return Produk::withoutGlobalScopes() // Remove any global scopes if exists
            ->orderBy('nama_produk')
            ->get()
            ->map(function ($produk) {
                return [
                    'nama_produk' => trim($produk->nama_produk), // Trim whitespace
                    'kategori_code' => $produk->kategori_code ?? '',
                    'id' => $produk->id,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kategori',
            'ID'
        ];
    }

    public function title(): string
    {
        return 'Master Data';
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '70AD47']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Lock the entire sheet
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword('qc123'); // Optional
            },
        ];
    }
}
