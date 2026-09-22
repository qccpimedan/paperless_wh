<?php

namespace App\Exports;

use App\Models\Produk;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReturnBarangTemplateUniversalExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ReturnBarangFormSheet(),
            new ReturnBarangMasterDataSheet(),
            new ReturnBarangCustomerSheet(),
        ];
    }
}

class ReturnBarangFormSheet implements 
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
            '', // Nama Produk (dropdown)
            '', // Kategori (VLOOKUP)
            '', // Customer (dropdown)
            '', // Alasan Return
            '', // Kondisi Produk
            '', // Suhu Produk
            '', // Kode Produksi
            '', // Expired Date
            '', // Jumlah Barang
            '', // Kondisi Kemasan
            '', // Kondisi Produk Check
            '', // Rekomendasi
            '', // Keterangan
            '', // ID Produk (Hidden - VLOOKUP)
            '', // ID Customer (Hidden - VLOOKUP)
        ]);
    }

    public function headings(): array
    {
        return [
            'NAMA PRODUK',
            'KATEGORI',
            'CUSTOMER',
            'ALASAN RETURN',
            'KONDISI PRODUK (Frozen/Fresh/Dry)',
            'SUHU PRODUK',
            'KODE PRODUKSI',
            'EXPIRED DATE (dd/mm/yyyy)',
            'JUMLAH BARANG',
            'KONDISI KEMASAN (Ya/Tidak)',
            'KONDISI PRODUK CHECK (Ya/Tidak)',
            'REKOMENDASI',
            'KETERANGAN',
            'ID_PRODUK',
            'ID_CUSTOMER'
        ];
    }

    public function title(): string
    {
        return 'Form Input';
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A4:O4')->applyFromArray([
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
                $sheet->mergeCells('A1:O1');
                $sheet->setCellValue('A1', 'TEMPLATE RETURN BARANG CUSTOMER - UNIVERSAL');
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
                $sheet->mergeCells('A2:O2');
                $sheet->setCellValue('A2', 'CARA MENGGUNAKAN: 1) Klik dropdown "NAMA PRODUK" dan pilih produk 2) Kategori akan terisi otomatis 3) Klik dropdown "CUSTOMER" dan pilih customer 4) Isi Alasan Return, Kondisi, dll 5) Simpan dan upload file ini');
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
                $sheet->getColumnDimension('A')->setWidth(45); // Nama Produk
                $sheet->getColumnDimension('B')->setWidth(15); // Kategori
                $sheet->getColumnDimension('C')->setWidth(25); // Customer
                $sheet->getColumnDimension('D')->setWidth(25); // Alasan Return
                $sheet->getColumnDimension('E')->setWidth(20); // Kondisi Produk
                $sheet->getColumnDimension('F')->setWidth(15); // Suhu Produk
                $sheet->getColumnDimension('G')->setWidth(20); // Kode Produksi
                $sheet->getColumnDimension('H')->setWidth(20); // Expired Date
                $sheet->getColumnDimension('I')->setWidth(18); // Jumlah Barang
                $sheet->getColumnDimension('J')->setWidth(20); // Kondisi Kemasan
                $sheet->getColumnDimension('K')->setWidth(22); // Kondisi Produk Check
                $sheet->getColumnDimension('L')->setWidth(25); // Rekomendasi
                $sheet->getColumnDimension('M')->setWidth(30); // Keterangan
                $sheet->getColumnDimension('N')->setWidth(10); // ID Produk (hidden)
                $sheet->getColumnDimension('O')->setWidth(10); // ID Customer (hidden)
                
                // Hide columns N and O (ID_PRODUK and ID_CUSTOMER)
                $sheet->getColumnDimension('N')->setVisible(false);
                $sheet->getColumnDimension('O')->setVisible(false);
                
                // Add VLOOKUP formulas for Kategori (B), ID_PRODUK (N), and ID_CUSTOMER (O)
                for ($row = 5; $row <= 34; $row++) {
                    // Kategori VLOOKUP
                    $sheet->setCellValue("B{$row}", 
                        "=IF(A{$row}=\"\",\"\",IFERROR(VLOOKUP(A{$row},'Master Data'!\$A:\$B,2,FALSE),\"\"))"
                    );
                    
                    // ID_PRODUK VLOOKUP (Hidden)
                    $sheet->setCellValue("N{$row}", 
                        "=IF(A{$row}=\"\",\"\",IFERROR(VLOOKUP(A{$row},'Master Data'!\$A:\$C,3,FALSE),\"\"))"
                    );
                    
                    // ID_CUSTOMER VLOOKUP (Hidden)
                    $sheet->setCellValue("O{$row}", 
                        "=IF(C{$row}=\"\",\"\",IFERROR(VLOOKUP(C{$row},'Master Customer'!\$A:\$B,2,FALSE),\"\"))"
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
                $validation->setFormula1("'Master Data'!\$A\$2:\$A\$5000");
                
                // Add Data Validation (Dropdown) for Customer column (C5:C34)
                $validationCustomer = $sheet->getDataValidation('C5:C34');
                $validationCustomer->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validationCustomer->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $validationCustomer->setAllowBlank(true);
                $validationCustomer->setShowInputMessage(true);
                $validationCustomer->setShowErrorMessage(true);
                $validationCustomer->setShowDropDown(true);
                $validationCustomer->setErrorTitle('Input Error');
                $validationCustomer->setError('Pilih customer dari dropdown');
                $validationCustomer->setPromptTitle('Pilih Customer');
                $validationCustomer->setPrompt('Klik dropdown untuk memilih customer');
                $validationCustomer->setFormula1("'Master Customer'!\$A\$2:\$A\$5000");
                
                // Lock protected columns (Kategori, ID Produk, ID Customer)
                $sheet->getStyle('B5:B34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
                $sheet->getStyle('N5:O34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
                
                // Unlock editable columns
                $sheet->getStyle('A5:A34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
                $sheet->getStyle('C5:M34')->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
                
                // Freeze panes
                $sheet->freezePane('B5');
                
                // Add borders to data area
                $sheet->getStyle('A4:M34')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC']
                        ]
                    ]
                ]);
            },
        ];
    }
}

class ReturnBarangMasterDataSheet implements 
    \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    public function collection()
    {
        $user = Auth::user();
        
        // Get products filtered by plant
        $query = Produk::with('user.plant')
            ->orderBy('nama_produk');
        
        // Filter by plant if not superadmin
        if ($user && $user->role && strtolower($user->role->role) !== 'superadmin') {
            $plantId = method_exists($user, 'getEffectivePlantId') ? $user->getEffectivePlantId() : $user->id_plant;
            
            $query->whereHas('user', function ($q) use ($plantId) {
                $q->where('id_plant', $plantId);
            });
        }
        
        return $query->get()
            ->map(function ($produk) {
                return [
                    'nama_produk' => trim($produk->nama_produk),
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
                
                // Lock the entire Master Data sheet
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword('qc123');
            },
        ];
    }
}


class ReturnBarangCustomerSheet implements 
    \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithEvents,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    public function collection()
    {
        $user = Auth::user();
        
        // Get customers filtered by plant
        $query = Customer::with('user.plant')
            ->orderBy('nama_cust');
        
        // Filter by plant if not superadmin
        if ($user && $user->role && strtolower($user->role->role) !== 'superadmin') {
            $plantId = method_exists($user, 'getEffectivePlantId') ? $user->getEffectivePlantId() : $user->id_plant;
            
            $query->whereHas('user', function ($q) use ($plantId) {
                $q->where('id_plant', $plantId);
            });
        }
        
        return $query->get()
            ->map(function ($customer) {
                return [
                    'nama_cust' => trim($customer->nama_cust),
                    'id' => $customer->id,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Customer',
            'ID'
        ];
    }

    public function title(): string
    {
        return 'Master Customer';
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F4B084']  // Orange color
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Lock the entire Master Customer sheet
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword('qc123');
            },
        ];
    }
}
