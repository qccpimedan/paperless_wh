<?php

namespace App\Exports;

use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemeriksaanSuhuRuangV3TemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithTitle, WithStyles
{
    protected $data = [];

    public function __construct()
    {
        // Hanya buat header dan beberapa baris contoh
        $this->data = [
            ['dd/mm/yyyy', '08:00', 'Shift 1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['dd/mm/yyyy', '08:00', 'Shift 2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
        ];
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'tanggal',
            'pukul',
            'shift',
            'suhu_premix_1_setting',
            'suhu_premix_1_display',
            'suhu_premix_1_actual',
            'suhu_premix_2_setting',
            'suhu_premix_2_display',
            'suhu_premix_2_actual',
            'suhu_premix_3_setting',
            'suhu_premix_3_display',
            'suhu_premix_3_actual',
            'suhu_premix_4_setting',
            'suhu_premix_4_display',
            'suhu_premix_4_actual',
            'suhu_seasoning_1_setting',
            'suhu_seasoning_1_display',
            'suhu_seasoning_1_actual',
            'suhu_seasoning_2_setting',
            'suhu_seasoning_2_display',
            'suhu_seasoning_2_actual',
            'suhu_seasoning_3_setting',
            'suhu_seasoning_3_display',
            'suhu_seasoning_3_actual',
            'suhu_seasoning_4_setting',
            'suhu_seasoning_4_display',
            'suhu_seasoning_4_actual',
            'suhu_dry_1_setting',
            'suhu_dry_1_display',
            'suhu_dry_1_actual',
            'suhu_dry_2_setting',
            'suhu_dry_2_display',
            'suhu_dry_2_actual',
            'keterangan',
            'tindakan_koreksi',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // tanggal
            'B' => 12,  // pukul
            'C' => 15,  // shift
            'D' => 18,  // suhu_premix_1_setting
            'E' => 18,  // suhu_premix_1_display
            'F' => 18,  // suhu_premix_1_actual
            'G' => 18,  // suhu_premix_2_setting
            'H' => 18,  // suhu_premix_2_display
            'I' => 18,  // suhu_premix_2_actual
            'J' => 18,  // suhu_premix_3_setting
            'K' => 18,  // suhu_premix_3_display
            'L' => 18,  // suhu_premix_3_actual
            'M' => 18,  // suhu_premix_4_setting
            'N' => 18,  // suhu_premix_4_display
            'O' => 18,  // suhu_premix_4_actual
            'P' => 20,  // suhu_seasoning_1_setting
            'Q' => 20,  // suhu_seasoning_1_display
            'R' => 20,  // suhu_seasoning_1_actual
            'S' => 20,  // suhu_seasoning_2_setting
            'T' => 20,  // suhu_seasoning_2_display
            'U' => 20,  // suhu_seasoning_2_actual
            'V' => 20,  // suhu_seasoning_3_setting
            'W' => 20,  // suhu_seasoning_3_display
            'X' => 20,  // suhu_seasoning_3_actual
            'Y' => 20,  // suhu_seasoning_4_setting
            'Z' => 20,  // suhu_seasoning_4_display
            'AA' => 20, // suhu_seasoning_4_actual
            'AB' => 18, // suhu_dry_1_setting
            'AC' => 18, // suhu_dry_1_display
            'AD' => 18, // suhu_dry_1_actual
            'AE' => 18, // suhu_dry_2_setting
            'AF' => 18, // suhu_dry_2_display
            'AG' => 18, // suhu_dry_2_actual
            'AH' => 30, // keterangan
            'AI' => 30, // tindakan_koreksi
        ];
    }

    public function title(): string
    {
        return 'Template Pemeriksaan V3';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2c3e50']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
            ],
        ];
    }
}
