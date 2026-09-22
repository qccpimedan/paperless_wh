<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReturnBarangUniversalSheetImport implements WithMultipleSheets
{
    private $importInstance;

    public function __construct()
    {
        $this->importInstance = new ReturnBarangUniversalImport();
    }

    public function sheets(): array
    {
        return [
            0 => $this->importInstance, // Only import Sheet 0 ("Form Input")
        ];
    }

    public function getImportInstance(): ReturnBarangUniversalImport
    {
        return $this->importInstance;
    }
}
