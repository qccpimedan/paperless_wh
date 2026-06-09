<?php

namespace App\Exports;

use App\Models\Produk;
use App\Models\Produsen;
use App\Models\Distributor;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukUpdateExport implements WithMultipleSheets
{
    protected $kategori_code;

    public function __construct($kategori_code)
    {
        $this->kategori_code = $kategori_code;
    }

    public function sheets(): array
    {
        return [
            new ProdukUpdateDataSheet($this->kategori_code),
            new ProdukUpdateReferenceSheet(),
        ];
    }
}

/**
 * Sheet Utama: Data Produk
 */
class ProdukUpdateDataSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $kategori_code;

    public function __construct($kategori_code)
    {
        $this->kategori_code = $kategori_code;
    }

    public function title(): string
    {
        return 'DATA PRODUK';
    }

    public function collection()
    {
        $plantId = Auth::user()->id_plant;
        
        return Produk::with([
            'produsens' => function ($q) use ($plantId) {
                $q->wherePivot('id_plant', $plantId);
            },
            'distributors' => function ($q) use ($plantId) {
                $q->wherePivot('id_plant', $plantId);
            }
        ])
        ->where('kategori_code', $this->kategori_code)
        ->get();
    }

    public function headings(): array
    {
        return [
            'ID SISTEM (JANGAN DIUBAH)',
            'NAMA PRODUK',
            'KATEGORI',
            'PRODUSEN (PISAH DENGAN ;)',
            'DISTRIBUTOR (PISAH DENGAN ;)',
        ];
    }

    public function map($produk): array
    {
        return [
            $produk->id,
            $produk->nama_produk,
            $produk->kategori_code,
            $produk->produsens->pluck('nama_produsen')->implode('; '),
            $produk->distributors->pluck('nama_distributor')->implode('; '),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '17a2b8']
                ]
            ],
        ];
    }
}

/**
 * Sheet Kedua: Daftar Referensi untuk Memudahkan User
 */
class ProdukUpdateReferenceSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    public function title(): string
    {
        return 'DAFTAR REFERENSI';
    }

    public function collection()
    {
        $user = Auth::user();
        $effectivePlantId = $user->getEffectivePlantId();

        // Ambil produsen yang terdaftar di plant ini
        $produsens = Produsen::whereHas('user', function ($query) use ($effectivePlantId) {
                $query->where('id_plant', $effectivePlantId);
            })
            ->orderBy('nama_produsen')
            ->pluck('nama_produsen');

        // Ambil distributor yang terdaftar di plant ini
        $distributors = Distributor::whereHas('user', function ($query) use ($effectivePlantId) {
                $query->where('id_plant', $effectivePlantId);
            })
            ->orderBy('nama_distributor')
            ->pluck('nama_distributor');

        $data = [];
        $max = max(count($produsens), count($distributors));

        for ($i = 0; $i < $max; $i++) {
            $data[] = [
                $produsens[$i] ?? '',
                $distributors[$i] ?? '',
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NAMA PRODUSEN (TERDAFTAR)',
            'NAMA DISTRIBUTOR (TERDAFTAR)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '6c757d']
                ]
            ],
        ];
    }
}
