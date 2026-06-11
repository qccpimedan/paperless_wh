<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LoadingUniversalSheetImport implements WithMultipleSheets
{
    private $importInstance;

    public function __construct()
    {
        // Create single instance and store reference
        $this->importInstance = new LoadingUniversalImport();
    }

    public function sheets(): array
    {
        return [
            0 => $this->importInstance, // Use stored instance
        ];
    }

    /**
     * Get the import instance to access data after import
     */
    public function getImportInstance()
    {
        return $this->importInstance;
    }
}
