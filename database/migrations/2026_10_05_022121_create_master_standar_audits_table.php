<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_standar_audits', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Module & Table Info
            $table->string('module_name')->index()->comment('Nama modul QC (e.g., pemeriksaan_suhu_ruang_v2)');
            $table->string('table_name')->nullable()->comment('Nama tabel database');
            $table->string('column_name')->comment('Nama kolom yang diaudit');
            
            // Area Specificity (Optional - untuk aturan khusus area)
            $table->unsignedBigInteger('area_id')->nullable()->index();
            $table->foreign('area_id')->references('id')->on('input_areas')->onDelete('cascade');
            
            // Parameter Configuration
            $table->enum('parameter_type', [
                'suhu',                 // Suhu produk
                'suhu_ruang',          // Suhu ruangan
                'suhu_kondisi',        // Suhu kondisi khusus (IQF, dll)
                'suhu_pusat',          // Suhu pusat produk
                'suhu_pemasakan',      // Suhu pemasakan/cooking
                'status_checklist',    // Status OK/NG/Checklist
            ])->default('status_checklist');
            
            // Value Constraints (untuk parameter numerik)
            $table->decimal('min_value', 8, 2)->nullable()->comment('Nilai minimum yang diperbolehkan');
            $table->decimal('max_value', 8, 2)->nullable()->comment('Nilai maximum yang diperbolehkan');
            $table->string('standar_value')->nullable()->comment('Nilai standar/target (bisa text atau numeric)');
            
            // Auto-Fix Configuration
            $table->boolean('auto_fix_status')->default(true)->comment('Auto-fix status NOT OK menjadi OK');
            
            $table->timestamps();
            
            // Composite Index untuk performa query
            $table->index(['module_name', 'column_name']);
            $table->index(['module_name', 'area_id', 'column_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_standar_audits');
    }
};
