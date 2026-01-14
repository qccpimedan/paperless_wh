<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdusenTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_produsen'];
    }

    public function array(): array
    {
        return [
            ['Contoh Produsen'],
        ];
    }
}
