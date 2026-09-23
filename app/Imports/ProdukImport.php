<?php

namespace App\Imports;

use App\Models\Produk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];
    public array $added_products = [];

    public function collection(Collection $rows)
    {
        $allowedKategori = ['WHSE', 'WHD2', 'WHDS', 'RT01', 'CR01', 'CR02', 'SHTS', 'SHCS', 'OTRM', 'SHCS & OTRM', 'CHEMICAL'];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $kategori = isset($row['kategori_code']) ? trim((string) $row['kategori_code']) : '';
            $nama = isset($row['nama_produk']) ? trim((string) $row['nama_produk']) : '';

            if ($kategori === '' || $nama === '') {
                $this->errors[] = "Baris {$rowNumber}: kategori_code dan nama_produk wajib diisi.";
                continue;
            }

            if (!in_array($kategori, $allowedKategori, true)) {
                $this->errors[] = "Baris {$rowNumber}: kategori_code '{$kategori}' tidak valid.";
                continue;
            }

            // FIX: Cek duplikasi berdasarkan kombinasi nama_produk + kategori_code
            $exists = Produk::query()
                ->where('nama_produk', $nama)
                ->where('kategori_code', $kategori)
                ->exists();

            if ($exists) {
                $this->errors[] = "Baris {$rowNumber}: Produk '{$nama}' dengan kategori '{$kategori}' sudah ada di database. Baris dilewati.";
                $this->skipped++;
                continue;
            }

            Produk::create([
                'id_user' => Auth::id(),
                'nama_produk' => $nama,
                'kategori_code' => $kategori,
            ]);

            $this->added_products[] = [
                'nama' => $nama,
                'kategori' => $kategori,
            ];
            $this->inserted++;
        }
    }
}
