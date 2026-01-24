<?php

namespace App\Imports;

use App\Models\Ekspedisi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EkspedisiImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $userPlantId = $user ? $user->id_plant : null;
        $isSuperadmin = $user && $user->role && strtolower((string) $user->role->role) === 'superadmin';

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nama = isset($row['nama_ekspedisi']) ? trim((string) $row['nama_ekspedisi']) : '';

            if ($nama === '') {
                $this->errors[] = "Baris {$rowNumber}: nama_ekspedisi wajib diisi.";
                continue;
            }

            $existsQuery = Ekspedisi::query()->whereRaw('LOWER(nama_ekspedisi) = ?', [mb_strtolower($nama)]);

            if (!$isSuperadmin && $userPlantId !== null) {
                $existsQuery->whereHas('user', function ($q) use ($userPlantId) {
                    $q->where('id_plant', $userPlantId);
                });
            }

            if ($existsQuery->exists()) {
                $this->skipped++;
                continue;
            }

            Ekspedisi::create([
                'id_user' => Auth::id(),
                'nama_ekspedisi' => $nama,
            ]);

            $this->inserted++;
        }
    }
}
