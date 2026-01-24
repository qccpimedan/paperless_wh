<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_barang', 'jumlah_barang', 'catatan'];
    }

    public function array(): array
    {
        return [
            ['Contoh Barang', 0, 'jumlah_barang hanya boleh angka'],
        ];
    }
}
