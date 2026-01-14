<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DistributorTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_distributor'];
    }

    public function array(): array
    {
        return [
            ['Contoh Distributor'],
        ];
    }
}
