<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SingleRecordViewExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly string $viewName,
        private readonly array $viewData,
        private readonly string $sheetTitle,
    ) {
    }

    public function view(): View
    {
        return view($this->viewName, $this->viewData);
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
}
