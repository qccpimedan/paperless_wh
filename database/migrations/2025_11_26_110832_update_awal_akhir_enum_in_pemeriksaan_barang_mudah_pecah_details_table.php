<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing data from old values to new values
        DB::statement("UPDATE pemeriksaan_barang_mudah_pecah_details SET awal = NULL WHERE awal IN ('ceklis', 'silang')");
        DB::statement("UPDATE pemeriksaan_barang_mudah_pecah_details SET akhir = NULL WHERE akhir IN ('ceklis', 'silang')");
        
        // Update ENUM values for awal and akhir columns
        DB::statement("ALTER TABLE pemeriksaan_barang_mudah_pecah_details MODIFY COLUMN awal ENUM('baik', 'tidak-baik') NULL");
        DB::statement("ALTER TABLE pemeriksaan_barang_mudah_pecah_details MODIFY COLUMN akhir ENUM('baik', 'tidak-baik') NULL");
    }

    public function down(): void
    {
        // Revert to old ENUM values
        DB::statement("ALTER TABLE pemeriksaan_barang_mudah_pecah_details MODIFY COLUMN awal ENUM('ceklis', 'silang') NULL");
        DB::statement("ALTER TABLE pemeriksaan_barang_mudah_pecah_details MODIFY COLUMN akhir ENUM('ceklis', 'silang') NULL");
    }
};