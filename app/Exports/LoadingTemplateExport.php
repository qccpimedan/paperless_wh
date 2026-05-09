<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LoadingTemplateExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $produk;

    public function __construct(Produk $produk)
    {
        $this->produk = $produk;
    }

    public function headings(): array
    {
        return [
            'KATEGORI',
            'NAMA PRODUK',
            'KODE PRODUKSI',
            'BEST BEFORE',
            'JUMLAH KEMASAN',
            'JUMLAH SAMPLING',
            'BERAT PER KARUNG & BOX',
            'Kondisi Kemasan Baik',
            'Keterangan'
        ];
    }

    public function array(): array
    {
        return [
            [
                $this->produk->kategori_code,
                $this->produk->nama_produk,
                '', // Kode Produksi
                date('d-M-y'), // Best Before (format 08-Apr-27)
                '1', // Jumlah Kemasan
                '', // Jumlah Sampling
                '20,6', // Berat per Karung
                'ok', // Kondisi Baik
                '' // Keterangan
            ],
        ];
    }

    public function title(): string
    {
        return 'Template ' . substr($this->produk->nama_produk, 0, 20);
    }
}
