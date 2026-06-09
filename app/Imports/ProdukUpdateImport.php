<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\Produsen;
use App\Models\Distributor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukUpdateImport implements ToCollection, WithHeadingRow
{
    public $updated_count = 0;
    public $updated_products = [];
    public $errors = [];

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $plantId = $user->getEffectivePlantId();

        foreach ($rows as $row) {
            $id = $row['id_sistem_jangan_diubah'] ?? null;
            
            if (empty($id)) continue;

            $produk = Produk::find($id);

            if (!$produk) {
                $this->errors[] = "Produk dengan ID {$id} tidak ditemukan.";
                continue;
            }

            $isUpdated = false;

            // Sync Produsen
            if (isset($row['produsen_pisah_dengan'])) {
                $produsenNames = explode(';', $row['produsen_pisah_dengan']);
                $produsenIds = [];
                
                foreach ($produsenNames as $name) {
                    $name = trim($name);
                    if (empty($name)) continue;

                    $produsen = Produsen::where('nama_produsen', 'LIKE', $name)->first();
                    
                    if ($produsen) {
                        $produsenIds[$produsen->id] = ['id_plant' => $plantId];
                    } else {
                        $this->errors[] = "Baris (ID {$id}): Produsen '{$name}' tidak ditemukan.";
                    }
                }
                $produk->produsens()->wherePivot('id_plant', $plantId)->sync($produsenIds);
                $isUpdated = true;
            }

            // Sync Distributor
            if (isset($row['distributor_pisah_dengan'])) {
                $distributorNames = explode(';', $row['distributor_pisah_dengan']);
                $distributorIds = [];
                
                foreach ($distributorNames as $name) {
                    $name = trim($name);
                    if (empty($name)) continue;

                    $distributor = Distributor::where('nama_distributor', 'LIKE', $name)->first();
                    
                    if ($distributor) {
                        $distributorIds[$distributor->id] = ['id_plant' => $plantId];
                    } else {
                        $this->errors[] = "Baris (ID {$id}): Distributor '{$name}' tidak ditemukan.";
                    }
                }
                $produk->distributors()->wherePivot('id_plant', $plantId)->sync($distributorIds);
                $isUpdated = true;
            }

            if ($isUpdated) {
                $this->updated_products[] = $produk->nama_produk;
                $this->updated_count++;
            }
        }
    }
}
