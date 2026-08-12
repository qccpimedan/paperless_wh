<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\Customer;
use App\Models\TujuanPengiriman;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class LoadingUniversalImport implements ToArray, WithHeadingRow, WithStartRow
{
    protected $produkData = [];
    protected $errors = [];

    public function startRow(): int
    {
        return 5; // Data starts at row 5
    }

    public function headingRow(): int
    {
        return 4; // Heading at row 4
    }

    public function array(array $rows)
    {
        $this->produkData = [];
        $this->errors = [];

        Log::info('=== IMPORT DEBUG START ===');
        Log::info('LoadingUniversalImport: Processing ' . count($rows) . ' rows');

        // Log first 3 rows to debug
        foreach (array_slice($rows, 0, 3, true) as $index => $row) {
            Log::info("Sample Row $index:", $row);
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 5; // Actual row number in Excel

            // Skip if Nama Produk is empty
            if (empty($row['nama_produk'])) {
                Log::info("Row $rowNumber: Skipped (empty nama_produk)");
                continue;
            }

            Log::info("Row $rowNumber: Processing product '{$row['nama_produk']}'");

            // Lookup by product name
            $produk = Produk::where('nama_produk', $row['nama_produk'])->first();
            
            if (!$produk) {
                $error = "Baris $rowNumber: Produk '{$row['nama_produk']}' tidak ditemukan di database";
                $this->errors[] = $error;
                Log::warning($error);
                continue;
            }

            $idProduk = $produk->id;
            Log::info("Row $rowNumber: Found product ID: $idProduk");

            // Parse kondisi kemasan
            $kondisiKemasan = true;
            $kondisiColumn = $row['kondisi_kemasan_oktidak'] ?? $row['kondisi_kemasan'] ?? null;
            
            if (!empty($kondisiColumn)) {
                $kondisiStr = strtolower(trim($kondisiColumn));
                if (in_array($kondisiStr, ['tidak', 'no', 'false', '0', 'rusak', 'buruk'])) {
                    $kondisiKemasan = false;
                }
            }

            // Parse best before date
            $bestBefore = null;
            $dateColumn = $row['best_before_dd_mmm_yy'] ?? $row['best_before'] ?? null;
            
            if (!empty($dateColumn)) {
                try {
                    $dateStr = trim($dateColumn);
                    
                    if (is_numeric($dateStr)) {
                        $bestBefore = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateStr)->format('Y-m-d');
                    } else {
                        $bestBefore = date('Y-m-d', strtotime($dateStr));
                    }
                } catch (\Exception $e) {
                    Log::warning("Row $rowNumber: Invalid date format");
                }
            }

            // Resolve Customer & Tujuan Pengiriman dari Excel
            $idTujuanPengiriman = null;
            $customerName = isset($row['customer']) ? trim($row['customer']) : '';
            $tujuanName = isset($row['tujuan_pengiriman']) ? trim($row['tujuan_pengiriman']) : '';

            if (!empty($customerName)) {
                $userId = Auth::id();
                
                // Cari atau buat customer
                $customer = Customer::firstOrCreate(
                    ['nama_cust' => $customerName],
                    ['id_user' => $userId]
                );

                // Default tujuan name to '-' jika kosong
                $tujuanResolvedName = !empty($tujuanName) ? $tujuanName : '-';

                // Cari atau buat tujuan pengiriman
                $tujuan = TujuanPengiriman::firstOrCreate(
                    [
                        'id_customer' => $customer->id,
                        'nama_tujuan' => $tujuanResolvedName
                    ],
                    ['id_user' => $userId]
                );
                
                $idTujuanPengiriman = $tujuan->id;
            }

            // Add to produk data
            $this->produkData[] = [
                'id_produk' => $idProduk,
                'nama_produk' => $produk->nama_produk,
                'kategori_code' => $produk->kategori_code,
                'id_tujuan_pengiriman' => $idTujuanPengiriman,
                'kode_produksi' => $row['kode_produksi'] ?? null,
                'best_before' => $bestBefore,
                'jumlah_kemasan' => $row['jumlah_kemasan'] ?? null,
                'jumlah_sampling' => $row['jumlah_sampling'] ?? null,
                'berat_perkarung' => $row['berat_per_karungbox'] ?? null,
                'kondisi_kemasan' => $kondisiKemasan,
                'keterangan' => $row['keterangan'] ?? null,
            ];

            Log::info("Row $rowNumber: Successfully added to produkData");
        }

        Log::info('=== IMPORT DEBUG END ===');
        Log::info('Total products: ' . count($this->produkData));
        Log::info('Total errors: ' . count($this->errors));

        return $rows;
    }

    public function getProdukData()
    {
        return $this->produkData;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
}
