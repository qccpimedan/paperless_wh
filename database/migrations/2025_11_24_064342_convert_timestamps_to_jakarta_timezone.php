<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert timestamps untuk semua tabel yang memiliki created_at dan updated_at
        // Dari UTC ke Asia/Jakarta (UTC+7)
        
        // Tabel pemeriksaan_suhu_ruang_v3s
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v3s 
            SET created_at = DATE_ADD(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_ADD(updated_at, INTERVAL 7 HOUR)
        ");
        
        // Tabel pemeriksaan_suhu_ruang_v3_histories
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v3_histories 
            SET created_at = DATE_ADD(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_ADD(updated_at, INTERVAL 7 HOUR)
        ");
        
        // Tabel pemeriksaan_suhu_ruang_v2s
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v2s 
            SET created_at = DATE_ADD(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_ADD(updated_at, INTERVAL 7 HOUR)
        ");
        
        // Tabel pemeriksaan_suhu_ruang_v2_histories
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v2_histories 
            SET created_at = DATE_ADD(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_ADD(updated_at, INTERVAL 7 HOUR)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to UTC
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v3s 
            SET created_at = DATE_SUB(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_SUB(updated_at, INTERVAL 7 HOUR)
        ");
        
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v3_histories 
            SET created_at = DATE_SUB(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_SUB(updated_at, INTERVAL 7 HOUR)
        ");
        
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v2s 
            SET created_at = DATE_SUB(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_SUB(updated_at, INTERVAL 7 HOUR)
        ");
        
        DB::statement("
            UPDATE pemeriksaan_suhu_ruang_v2_histories 
            SET created_at = DATE_SUB(created_at, INTERVAL 7 HOUR),
                updated_at = DATE_SUB(updated_at, INTERVAL 7 HOUR)
        ");
    }
};