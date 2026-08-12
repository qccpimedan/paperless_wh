<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PemeriksaanLoadingProdukExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $pemeriksaans;
    protected $produkNamaById;
    protected $qcUser;
    protected $produksiUser;
    protected $spvQcUser;

    public function __construct($pemeriksaans, $produkNamaById, $qcUser = null, $produksiUser = null, $spvQcUser = null)
    {
        $this->pemeriksaans = $pemeriksaans;
        $this->produkNamaById = $produkNamaById;
        $this->qcUser = $qcUser;
        $this->produksiUser = $produksiUser;
        $this->spvQcUser = $spvQcUser;
    }

    public function view(): View
    {
        return view('qc-sistem.pemeriksaan-loading-produk.excel-export', [
            'pemeriksaans' => $this->pemeriksaans,
            'produkNamaById' => $this->produkNamaById,
            'qcUser' => $this->qcUser,
            'produksiUser' => $this->produksiUser,
            'spvQcUser' => $this->spvQcUser,
        ]);
    }

    public function title(): string
    {
        return 'Pemeriksaan Loading Produk';
    }
    public function columnWidths(): array
    {
        return [
            'A' => 30,  // No - diperbesar agar tidak terpotong
            'B' => 35,  // Lokasi
            'C' => 45,  // Sebelumnya
            'D' => 45,  // Sesudahnya
        ];
    }
}
