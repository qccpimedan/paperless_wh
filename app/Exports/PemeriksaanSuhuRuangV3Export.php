<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PemeriksaanSuhuRuangV3Export implements FromView, WithTitle, ShouldAutoSize, WithColumnWidths
{
    protected $pemeriksaans;
    protected $tanggal;
    protected $tanggalDari;
    protected $tanggalSampai;
    protected $shift;
    protected $qcUser;
    protected $produksiUser;
    protected $spvQcUser;

    public function __construct($pemeriksaans, $params = [])
    {
        $this->pemeriksaans = $pemeriksaans;
        $this->tanggal = $params['tanggal'] ?? null;
        $this->tanggalDari = $params['tanggal_dari'] ?? null;
        $this->tanggalSampai = $params['tanggal_sampai'] ?? null;
        $this->shift = $params['shift'] ?? null;
        $this->qcUser = $params['qcUser'] ?? null;
        $this->produksiUser = $params['produksiUser'] ?? null;
        $this->spvQcUser = $params['spvQcUser'] ?? null;
    }

    public function view(): View
    {
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.excel-export', [
            'pemeriksaans' => $this->pemeriksaans,
            'tanggal' => $this->tanggal,
            'tanggal_dari' => $this->tanggalDari,
            'tanggal_sampai' => $this->tanggalSampai,
            'shift' => $this->shift,
            'qcUser' => $this->qcUser,
            'produksiUser' => $this->produksiUser,
            'spvQcUser' => $this->spvQcUser,
        ]);
    }

    public function title(): string
    {
        return 'Pemeriksaan Suhu Ruang V3';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 25,  // Lokasi
            'C' => 15,  // Setting
            'D' => 15,  // Display
            'E' => 15,  // Actual
            'F' => 12,  // Status
        ];
    }
}
