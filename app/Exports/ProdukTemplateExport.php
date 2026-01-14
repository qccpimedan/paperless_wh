<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdukTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['kategori_code', 'nama_produk'];
    }

    public function array(): array
    {
        return [
            ['WHSE', 'Contoh Produk'],
        ];
    }
}
