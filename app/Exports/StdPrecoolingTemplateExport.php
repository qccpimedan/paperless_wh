<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StdPrecoolingTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_std_precooling'];
    }

    public function array(): array
    {
        return [
            ['Contoh Std Precooling'],
        ];
    }
}
