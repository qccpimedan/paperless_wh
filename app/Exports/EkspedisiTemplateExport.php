<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EkspedisiTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_ekspedisi'];
    }

    public function array(): array
    {
        return [
            ['Contoh Ekspedisi'],
        ];
    }
}
