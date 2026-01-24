<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToCollection, WithHeadingRow
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

            $nama = isset($row['nama_barang']) ? trim((string) $row['nama_barang']) : '';
            $jumlahRaw = $row['jumlah_barang'] ?? null;

            if ($nama === '') {
                $this->errors[] = "Baris {$rowNumber}: nama_barang wajib diisi.";
                continue;
            }

            $jumlah = 0;
            if ($jumlahRaw !== null && $jumlahRaw !== '') {
                if (!is_numeric($jumlahRaw)) {
                    $this->errors[] = "Baris {$rowNumber}: jumlah_barang harus angka.";
                    continue;
                }
                $jumlah = (int) $jumlahRaw;
                if ($jumlah < 0) {
                    $this->errors[] = "Baris {$rowNumber}: jumlah_barang minimal 0.";
                    continue;
                }
            }

            $existsQuery = Barang::query()->whereRaw('LOWER(nama_barang) = ?', [mb_strtolower($nama)]);

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

            Barang::create([
                'id_user' => Auth::id(),
                'nama_barang' => $nama,
                'jumlah_barang' => $jumlah,
            ]);

            $this->inserted++;
        }
    }
}
