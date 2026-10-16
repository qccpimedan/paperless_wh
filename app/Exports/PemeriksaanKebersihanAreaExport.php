<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PemeriksaanKebersihanAreaExport implements FromView, WithTitle, ShouldAutoSize, WithColumnWidths
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
        return view('qc-sistem.pemeriksaan-kebersihan-area.excel-export', [
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
        return 'Pemeriksaan Kebersihan Area';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 35,  // Aspek Yang Dinilai
            'C' => 12,  // Sebelum
            'D' => 12,  // Sesudah
            'E' => 12,  // Verifikasi
            'F' => 30,  // Keterangan
            'G' => 30,  // Tindakan
        ];
    }
}
