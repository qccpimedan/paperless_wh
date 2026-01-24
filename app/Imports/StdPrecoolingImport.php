<?php

namespace App\Imports;

use App\Models\StdPrecooling;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StdPrecoolingImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nama = isset($row['nama_std_precooling']) ? trim((string) $row['nama_std_precooling']) : '';

            if ($nama === '') {
                $this->errors[] = "Baris {$rowNumber}: nama_std_precooling wajib diisi.";
                continue;
            }

            $exists = StdPrecooling::query()
                ->whereRaw('LOWER(nama_std_precooling) = ?', [mb_strtolower($nama)])
                ->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            StdPrecooling::create([
                'id_user' => Auth::id(),
                'nama_std_precooling' => $nama,
            ]);

            $this->inserted++;
        }
    }
}
