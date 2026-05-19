<?php

namespace App\Exports;

use App\Models\InputArea;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class BarangTemplateExport implements FromArray, WithHeadings, WithEvents
{
    protected $areas = [];

    public function headings(): array
    {
        return ['nama_barang', 'jumlah_barang', 'area', 'catatan'];
    }

    public function array(): array
    {
        $user = Auth::user();
        
        $areaQuery = InputArea::query();
        if ($user && $user->role && strtolower($user->role->role) !== 'superadmin') {
            $areaQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }
        
        $this->areas = $areaQuery->orderBy('nama_area')->pluck('nama_area')->toArray();
        
        // Dynamic example area from master database
        $exampleArea = (count($this->areas) > 0) ? $this->areas[0] : 'Gudang Clean Room';

        return [
            ['Kaca Jendela Kap', 5, $exampleArea, ''],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $areaList = $this->areas;
                if (empty($areaList)) {
                    $areaList = ['Tanpa Area'];
                }

                // Write the list of active areas to the hidden column Z
                foreach ($areaList as $index => $areaName) {
                    $rowNum = $index + 1;
                    $event->sheet->setCellValue("Z{$rowNum}", $areaName);
                }

                // Hide column Z to keep it clean and out of the user's view
                $event->sheet->getColumnDimension('Z')->setVisible(false);

                // Initialize Excel Data Validation on cell C2
                $validation = $event->sheet->getCell('C2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input Error');
                $validation->setError('Area tidak valid! Silakan pilih dari dropdown list.');
                
                // Point the validation formula to our hidden cell range
                $validation->setFormula1('=$Z$1:$Z$' . count($areaList));
                
                // Apply the validation to column range C2:C200
                $validation->setSqref('C2:C200');
            },
        ];
    }
}
