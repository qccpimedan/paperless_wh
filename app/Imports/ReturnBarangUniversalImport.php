<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ReturnBarangUniversalImport implements ToCollection, WithStartRow
{
    public $produkData = [];
    public $errors = [];
    public $totalRows = 0;
    public $validRows = 0;

    /**
     * Start from row 5 (skip title, instructions, empty row, and header)
     */
    public function startRow(): int
    {
        return 5;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $this->totalRows = $rows->count();
        $rowNumber = 5; // Start from 5 (actual data row in Excel)

        foreach ($rows as $row) {
            try {
                // Skip empty rows
                if ($this->isRowEmpty($row)) {
                    $rowNumber++;
                    continue;
                }

                // Validate and process row
                $validated = $this->validateRow($row, $rowNumber);
                
                if ($validated['valid']) {
                    $this->produkData[] = $validated['data'];
                    $this->validRows++;
                } else {
                    $this->errors[] = "Baris {$rowNumber}: " . $validated['error'];
                }

            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNumber}: Error - " . $e->getMessage();
            }

            $rowNumber++;
        }
    }

    /**
     * Check if row is empty
     */
    private function isRowEmpty($row): bool
    {
        if (!$row) return true;
        
        // A row is empty if Nama Produk (Column A, index 0) is empty or starts with '='
        $namaProduk = trim((string)($row[0] ?? ''));
        if (empty($namaProduk) || str_starts_with($namaProduk, '=')) {
            return true;
        }
        
        return false;
    }

    /**
     * Validate and transform row data
     */
    private function validateRow($row, $rowNumber): array
    {
        // Map columns by index (0-based)
        // A=0: Nama Produk, B=1: Kategori, C=2: Customer, D=3: Alasan Return, etc.
        
        // 1. Validate Nama Produk (Column A, index 0)
        $namaProduk = trim((string)($row[0] ?? ''));
        if (empty($namaProduk) || str_starts_with($namaProduk, '=')) {
            return ['valid' => false, 'error' => 'Nama Produk wajib diisi'];
        }

        // Clean invisible whitespace (e.g. non-breaking spaces & extra spaces)
        $cleanNamaProduk = preg_replace('/\s+/', ' ', trim(str_replace("\xc2\xa0", ' ', $namaProduk)));

        // Find produk by numeric ID column (Column N, index 13) or by name
        $produkId = $row[13] ?? null; // Hidden ID_PRODUK column
        $produk = null;
        if (is_numeric($produkId) && (int)$produkId > 0) {
            $produk = Produk::find((int)$produkId);
        }
        
        if (!$produk) {
            $produk = Produk::where('nama_produk', $cleanNamaProduk)
                ->orWhereRaw('LOWER(TRIM(nama_produk)) = ?', [mb_strtolower($cleanNamaProduk)])
                ->orWhere('nama_produk', 'LIKE', "%{$cleanNamaProduk}%")
                ->first();
        }
        
        if (!$produk) {
            return ['valid' => false, 'error' => "Produk '{$namaProduk}' tidak ditemukan di database"];
        }

        // 2. Validate Customer (Column C, index 2)
        $customerName = trim((string)($row[2] ?? ''));
        if (empty($customerName) || str_starts_with($customerName, '=')) {
            return ['valid' => false, 'error' => 'Customer wajib diisi'];
        }

        $cleanCustomerName = preg_replace('/\s+/', ' ', trim(str_replace("\xc2\xa0", ' ', $customerName)));

        // Find customer by numeric ID column (Column O, index 14) or by name
        $customerId = $row[14] ?? null; // Hidden ID_CUSTOMER column
        $customer = null;
        if (is_numeric($customerId) && (int)$customerId > 0) {
            $customer = Customer::find((int)$customerId);
        }
        
        if (!$customer) {
            $customer = Customer::where('nama_cust', $cleanCustomerName)
                ->orWhereRaw('LOWER(TRIM(nama_cust)) = ?', [mb_strtolower($cleanCustomerName)])
                ->orWhere('nama_cust', 'LIKE', "%{$cleanCustomerName}%")
                ->first();
        }
        
        if (!$customer) {
            return ['valid' => false, 'error' => "Customer '{$customerName}' tidak ditemukan"];
        }

        // 3. Validate Alasan Return (Column D, index 3)
        $alasanReturn = trim($row[3] ?? '');
        if (empty($alasanReturn)) {
            $alasanReturn = '-'; // Fallback default if empty in Excel
        }

        // 4. Validate Kondisi Produk (Column E, index 4)
        $kondisiProdukRaw = trim($row[4] ?? '');
        $kondisiProduk = ucfirst(strtolower($kondisiProdukRaw));
        $allowedKondisi = ['Frozen', 'Fresh', 'Dry'];
        if (!in_array($kondisiProduk, $allowedKondisi)) {
            $kondisiProduk = 'Fresh'; // Fallback default
        }

        // 5. Validate Kode Produksi (Column G, index 6)
        $kodeProduksi = trim($row[6] ?? '');
        if (empty($kodeProduksi)) {
            $kodeProduksi = '-';
        }

        // 6. Validate Expired Date (Column H, index 7)
        $expiredDateVal = $row[7] ?? '';
        $expiredDate = null;
        if (!empty($expiredDateVal)) {
            try {
                if (is_numeric($expiredDateVal)) {
                    $expiredDate = Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($expiredDateVal)->format('Y-m-d'));
                } else {
                    $dateStr = trim((string)$expiredDateVal);
                    if (strpos($dateStr, '/') !== false) {
                        $parts = explode('/', $dateStr);
                        if (count($parts) === 3) {
                            if (strlen($parts[2]) === 4) {
                                $expiredDate = Carbon::createFromDate((int)$parts[2], (int)$parts[1], (int)$parts[0]);
                            } else {
                                $expiredDate = Carbon::parse($dateStr);
                            }
                        } else {
                            $expiredDate = Carbon::parse($dateStr);
                        }
                    } else {
                        $expiredDate = Carbon::parse($dateStr);
                    }
                }
            } catch (\Exception $e) {
                $expiredDate = Carbon::now();
            }
        }
        if (!$expiredDate) {
            $expiredDate = Carbon::now();
        }

        // 7. Validate Jumlah Barang (Column I, index 8)
        $jumlahBarang = trim($row[8] ?? '');
        if (empty($jumlahBarang)) {
            $jumlahBarang = '1';
        }

        // 8. Validate Kondisi Kemasan (Column J, index 9)
        $kondisiKemasanRaw = strtolower(trim($row[9] ?? ''));
        $kondisiKemasan = (in_array($kondisiKemasanRaw, ['tidak', 'no', '0'])) ? 'tidak' : 'ya';

        // 9. Validate Kondisi Produk Check (Column K, index 10)
        $kondisiProdukCheckRaw = strtolower(trim($row[10] ?? ''));
        $kondisiProdukCheck = (in_array($kondisiProdukCheckRaw, ['tidak', 'no', '0'])) ? 'tidak' : 'ya';

        // 10. Validate Rekomendasi (Column L, index 11)
        $rekomendasi = trim($row[11] ?? '');
        if (empty($rekomendasi)) {
            $rekomendasi = '-';
        }

        // Build data array
        $data = [
            'id_produk' => $produk->id,
            'nama_produk' => $produk->nama_produk,
            'kategori_code' => $produk->kategori_code,
            'id_customer' => $customer->id,
            'alasan_return' => $alasanReturn,
            'kondisi_produk' => $kondisiProduk,
            'suhu_produk' => trim($row[5] ?? ''), // Column F, index 5
            'kode_produksi' => $kodeProduksi,
            'expired_date' => $expiredDate->format('Y-m-d'),
            'jumlah_barang' => $jumlahBarang,
            'kondisi_kemasan' => $kondisiKemasan === 'ya' ? 1 : 0,
            'kondisi_produk_check' => $kondisiProdukCheck === 'ya' ? 1 : 0,
            'rekomendasi' => $rekomendasi,
            'keterangan' => trim($row[12] ?? ''), // Column M, index 12
        ];

        return ['valid' => true, 'data' => $data];
    }
}
