<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InputDeskripsiTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_deskripsi'];
    }

    public function array(): array
    {
        return [
            ['Contoh Deskripsi'],
        ];
    }
}
