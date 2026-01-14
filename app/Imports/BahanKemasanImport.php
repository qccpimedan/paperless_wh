<?php

namespace App\Imports;

use App\Models\BahanKemasan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BahanKemasanImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $allowedKategori = ['WHD2', 'WHDS'];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $kategori = isset($row['kategori_code']) ? trim((string) $row['kategori_code']) : '';
            $nama = isset($row['nama_kemasan']) ? trim((string) $row['nama_kemasan']) : '';

            if ($kategori === '' || $nama === '') {
                $this->errors[] = "Baris {$rowNumber}: kategori_code dan nama_kemasan wajib diisi.";
                continue;
            }

            if (!in_array($kategori, $allowedKategori, true)) {
                $this->errors[] = "Baris {$rowNumber}: kategori_code '{$kategori}' tidak valid.";
                continue;
            }

            $exists = BahanKemasan::query()
                ->whereRaw('LOWER(nama_kemasan) = ?', [mb_strtolower($nama)])
                ->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            BahanKemasan::create([
                'id_user' => Auth::id(),
                'nama_kemasan' => $nama,
                'kategori_code' => $kategori,
            ]);

            $this->inserted++;
        }
    }
}
