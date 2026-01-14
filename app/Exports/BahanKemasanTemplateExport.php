<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BahanKemasanTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['kategori_code', 'nama_kemasan'];
    }

    public function array(): array
    {
        return [
            ['WHD2', 'Contoh Kemasan'],
        ];
    }
}
