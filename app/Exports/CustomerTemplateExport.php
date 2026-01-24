<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_cust'];
    }

    public function array(): array
    {
        return [
            ['Contoh Customer'],
        ];
    }
}
