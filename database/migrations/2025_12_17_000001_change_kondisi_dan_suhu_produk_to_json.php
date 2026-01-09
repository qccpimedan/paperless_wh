<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Untuk MySQL, kita perlu drop kolom lama dan buat kolom baru dengan tipe JSON
        if (DB::getDriverName() === 'mysql') {
            // Cek apakah kolom sudah ada dan bertipe JSON
            $columns = DB::select("SHOW COLUMNS FROM pemeriksaan_kedatangan_bahan_baku_penunjangs WHERE Field IN ('kondisi_produk', 'suhu_produk', 'suhu_produk_type', 'kondisi_produk_suhu')");
            
            foreach ($columns as $column) {
                // Jika kolom sudah JSON, skip
                if (stripos($column->Type, 'json') !== false) {
                    continue;
                }
                
                // Drop dan recreate kolom
                DB::statement("ALTER TABLE pemeriksaan_kedatangan_bahan_baku_penunjangs DROP COLUMN {$column->Field}");
                
                // Tentukan posisi kolom
                $afterColumn = 'no_po';
                if ($column->Field === 'suhu_produk') {
                    $afterColumn = 'kondisi_produk';
                } elseif ($column->Field === 'suhu_produk_type') {
                    $afterColumn = 'suhu_produk';
                } elseif ($column->Field === 'kondisi_produk_suhu') {
                    $afterColumn = 'suhu_produk_type';
                }
                
                DB::statement("ALTER TABLE pemeriksaan_kedatangan_bahan_baku_penunjangs ADD COLUMN {$column->Field} JSON NULL AFTER {$afterColumn}");
            }
        }
    }

    public function down()
    {
        // Revert - drop kolom JSON dan buat kembali sebagai VARCHAR
        if (DB::getDriverName() === 'mysql') {
            // Cek kolom yang ada
            $existingColumns = DB::select("SHOW COLUMNS FROM pemeriksaan_kedatangan_bahan_baku_penunjangs WHERE Field IN ('kondisi_produk', 'suhu_produk', 'suhu_produk_type', 'kondisi_produk_suhu')");
            
            // Drop kolom yang ada
            foreach ($existingColumns as $column) {
                DB::statement("ALTER TABLE pemeriksaan_kedatangan_bahan_baku_penunjangs DROP COLUMN {$column->Field}");
            }
            
            // Recreate sebagai VARCHAR
            DB::statement('ALTER TABLE pemeriksaan_kedatangan_bahan_baku_penunjangs ADD COLUMN kondisi_produk VARCHAR(255) NULL AFTER no_po');
            DB::statement('ALTER TABLE pemeriksaan_kedatangan_bahan_baku_penunjangs ADD COLUMN suhu_produk VARCHAR(255) NULL AFTER kondisi_produk');
            DB::statement('ALTER TABLE pemeriksaan_kedatangan_bahan_baku_penunjangs ADD COLUMN suhu_produk_type VARCHAR(255) NULL AFTER suhu_produk');
            DB::statement('ALTER TABLE pemeriksaan_kedatangan_bahan_baku_penunjangs ADD COLUMN kondisi_produk_suhu VARCHAR(255) NULL AFTER suhu_produk_type');
        }
    }
};
