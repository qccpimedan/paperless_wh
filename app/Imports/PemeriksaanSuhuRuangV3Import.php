<?php

namespace App\Imports;

use App\Models\PemeriksaanSuhuRuangV3;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PemeriksaanSuhuRuangV3Import implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $skipped = 0;
    public int $errors_count = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $userPlantId = $user->getEffectivePlantId();

        $suhuFields = [
            'suhu_premix',
            'suhu_seasoning',
            'suhu_dry',
            'suhu_cassing',
            'suhu_beef',
            'suhu_packaging',
            'suhu_ruang_chemical',
            'suhu_ruang_seasoning',
        ];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            try {
                // Validasi kolom wajib
                $tanggalStr = isset($row['tanggal']) ? trim((string) $row['tanggal']) : '';
                $pukulStr = isset($row['pukul']) ? trim((string) $row['pukul']) : '';
                $shiftName = isset($row['shift']) ? trim((string) $row['shift']) : '';

                if (empty($tanggalStr)) {
                    $this->errors[] = "Baris {$rowNumber}: Tanggal wajib diisi.";
                    $this->errors_count++;
                    continue;
                }

                if (empty($pukulStr)) {
                    $this->errors[] = "Baris {$rowNumber}: Pukul wajib diisi.";
                    $this->errors_count++;
                    continue;
                }

                if (empty($shiftName)) {
                    $this->errors[] = "Baris {$rowNumber}: Shift wajib diisi.";
                    $this->errors_count++;
                    continue;
                }

                // Parse tanggal
                try {
                    // Support multiple date formats: DD/MM/YYYY, YYYY-MM-DD, DD-MM-YYYY
                    if (strpos($tanggalStr, '/') !== false) {
                        $parts = explode('/', $tanggalStr);
                        if (count($parts) === 3) {
                            $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalStr)->format('Y-m-d');
                        } else {
                            throw new \Exception("Format tanggal tidak valid");
                        }
                    } else {
                        $tanggal = \Carbon\Carbon::parse($tanggalStr)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $this->errors[] = "Baris {$rowNumber}: Format tanggal tidak valid. Gunakan format DD/MM/YYYY atau YYYY-MM-DD.";
                    $this->errors_count++;
                    continue;
                }

                // Parse pukul (HH:MM)
                if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $pukulStr)) {
                    $this->errors[] = "Baris {$rowNumber}: Format pukul tidak valid. Gunakan format HH:MM.";
                    $this->errors_count++;
                    continue;
                }

                // Cari shift berdasarkan nama
                $shift = Shift::whereRaw('LOWER(shift) = ?', [mb_strtolower($shiftName)])->first();
                if (!$shift) {
                    $this->errors[] = "Baris {$rowNumber}: Shift '{$shiftName}' tidak ditemukan.";
                    $this->errors_count++;
                    continue;
                }

                // Cek duplikasi
                $exists = PemeriksaanSuhuRuangV3::where('tanggal', $tanggal)
                    ->where('pukul', $pukulStr)
                    ->where('id_shift', $shift->id)
                    ->whereHas('user', function ($q) use ($userPlantId) {
                        $q->where('id_plant', $userPlantId);
                    })
                    ->exists();

                if ($exists) {
                    $this->skipped++;
                    continue;
                }

                // Persiapkan data suhu
                $suhuData = [];
                foreach ($suhuFields as $field) {
                    $fieldData = [];

                    // Baca unit 1-4 untuk field tersebut
                    for ($unit = 1; $unit <= 4; $unit++) {
                        $settingKey = $field . '_' . $unit . '_setting';
                        $displayKey = $field . '_' . $unit . '_display';
                        $actualKey = $field . '_' . $unit . '_actual';

                        $setting = isset($row[$settingKey]) ? trim((string) $row[$settingKey]) : '';
                        $display = isset($row[$displayKey]) ? trim((string) $row[$displayKey]) : '';
                        $actual = isset($row[$actualKey]) ? trim((string) $row[$actualKey]) : '';

                        // Skip jika semua kosong
                        if (empty($setting) && empty($display) && empty($actual)) {
                            continue;
                        }

                        // Validasi hanya numeric atau nilai standard
                        if (!empty($display) && !is_numeric($display)) {
                            $this->errors[] = "Baris {$rowNumber}: {$field} Unit {$unit} Display harus angka, diperoleh '{$display}'.";
                            $this->errors_count++;
                            throw new \Exception("Invalid display value");
                        }

                        if (!empty($actual) && !is_numeric($actual)) {
                            $this->errors[] = "Baris {$rowNumber}: {$field} Unit {$unit} Actual harus angka, diperoleh '{$actual}'.";
                            $this->errors_count++;
                            throw new \Exception("Invalid actual value");
                        }

                        $itemData = [];
                        if (!empty($setting)) {
                            $itemData['setting'] = $setting;
                        }
                        if (!empty($display)) {
                            $itemData['display'] = $display;
                        }
                        if (!empty($actual)) {
                            $itemData['actual'] = $actual;
                        }

                        if (!empty($itemData)) {
                            $fieldData[] = $itemData;
                        }
                    }

                    if (!empty($fieldData)) {
                        $suhuData[$field] = $fieldData;
                    }
                }

                // Simpan record
                $keterangan = isset($row['keterangan']) ? trim((string) $row['keterangan']) : '';
                $tindakanKoreksi = isset($row['tindakan_koreksi']) ? trim((string) $row['tindakan_koreksi']) : '';

                PemeriksaanSuhuRuangV3::create([
                    'id_user' => $user->id,
                    'id_shift' => $shift->id,
                    'tanggal' => $tanggal,
                    'pukul' => $pukulStr,
                    'suhu_premix' => !empty($suhuData['suhu_premix']) ? json_encode($suhuData['suhu_premix']) : null,
                    'suhu_seasoning' => !empty($suhuData['suhu_seasoning']) ? json_encode($suhuData['suhu_seasoning']) : null,
                    'suhu_dry' => !empty($suhuData['suhu_dry']) ? json_encode($suhuData['suhu_dry']) : null,
                    'suhu_cassing' => !empty($suhuData['suhu_cassing']) ? json_encode($suhuData['suhu_cassing']) : null,
                    'suhu_beef' => !empty($suhuData['suhu_beef']) ? json_encode($suhuData['suhu_beef']) : null,
                    'suhu_packaging' => !empty($suhuData['suhu_packaging']) ? json_encode($suhuData['suhu_packaging']) : null,
                    'suhu_ruang_chemical' => !empty($suhuData['suhu_ruang_chemical']) ? json_encode($suhuData['suhu_ruang_chemical']) : null,
                    'suhu_ruang_seasoning' => !empty($suhuData['suhu_ruang_seasoning']) ? json_encode($suhuData['suhu_ruang_seasoning']) : null,
                    'keterangan' => $keterangan,
                    'tindakan_koreksi' => $tindakanKoreksi,
                ]);

                $this->inserted++;
            } catch (\Exception $e) {
                if (!in_array("Baris {$rowNumber}: " . $e->getMessage(), $this->errors)) {
                    $this->errors[] = "Baris {$rowNumber}: Kesalahan tidak terduga - " . $e->getMessage();
                    $this->errors_count++;
                }
            }
        }
    }
}
