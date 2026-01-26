<?php

namespace App\Imports;

use App\Models\InputDeskripsi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InputDeskripsiImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nama = isset($row['nama_deskripsi']) ? trim((string) $row['nama_deskripsi']) : '';

            if ($nama === '') {
                $this->errors[] = "Baris {$rowNumber}: nama_deskripsi wajib diisi.";
                continue;
            }

            $exists = InputDeskripsi::query()
                ->whereRaw('LOWER(nama_deskripsi) = ?', [mb_strtolower($nama)])
                ->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            InputDeskripsi::create([
                'id_user' => Auth::id(),
                'nama_deskripsi' => $nama,
            ]);

            $this->inserted++;
        }
    }
}
