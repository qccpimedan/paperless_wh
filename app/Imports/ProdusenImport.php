<?php

namespace App\Imports;

use App\Models\Produsen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdusenImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nama = isset($row['nama_produsen']) ? trim((string) $row['nama_produsen']) : '';

            if ($nama === '') {
                $this->errors[] = "Baris {$rowNumber}: nama_produsen wajib diisi.";
                continue;
            }

            $exists = Produsen::query()
                ->whereRaw('LOWER(nama_produsen) = ?', [mb_strtolower($nama)])
                ->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            Produsen::create([
                'id_user' => Auth::id(),
                'nama_produsen' => $nama,
            ]);

            $this->inserted++;
        }
    }
}
