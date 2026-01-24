<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToCollection, WithHeadingRow
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

            $nama = isset($row['nama_cust']) ? trim((string) $row['nama_cust']) : '';

            if ($nama === '') {
                $this->errors[] = "Baris {$rowNumber}: nama_cust wajib diisi.";
                continue;
            }

            $existsQuery = Customer::query()->whereRaw('LOWER(nama_cust) = ?', [mb_strtolower($nama)]);

            if (!$isSuperadmin && $userPlantId !== null) {
                $existsQuery->whereHas('user', function ($q) use ($userPlantId) {
                    $q->where('id_plant', $userPlantId);
                });
            }

            $exists = $existsQuery->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            Customer::create([
                'id_user' => Auth::id(),
                'nama_cust' => $nama,
            ]);

            $this->inserted++;
        }
    }
}
