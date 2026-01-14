<?php

namespace App\Imports;

use App\Models\Distributor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DistributorImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nama = isset($row['nama_distributor']) ? trim((string) $row['nama_distributor']) : '';

            if ($nama === '') {
                $this->errors[] = "Baris {$rowNumber}: nama_distributor wajib diisi.";
                continue;
            }

            $exists = Distributor::query()
                ->whereRaw('LOWER(nama_distributor) = ?', [mb_strtolower($nama)])
                ->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            Distributor::create([
                'id_user' => Auth::id(),
                'nama_distributor' => $nama,
            ]);

            $this->inserted++;
        }
    }
}
