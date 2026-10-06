<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * List of QC module tables yang akan ditambahkan audit fields
     */
    protected array $tables = [
        // Priority HIGH
        'pemeriksaan_suhu_ruangs',
        'pemeriksaan_suhu_ruang_v2s',
        'pemeriksaan_suhu_ruang_v3s',
        
        // Priority MEDIUM
        'pemeriksaan_kebersihan_areas',
        'pemeriksaan_loading_produks',
        'pemeriksaan_produk_finish_goods',
        
        // Priority LOW
        'pemeriksaan_loading_kendaraans',
        'pemeriksaan_barang_mudah_pecahs',
        'pemeriksaan_return_barang_customers',
        'pemeriksaan_kedatangan_kemasans',
        'pemeriksaan_kedatangan_chemicals',
        'pemeriksaan_kedatangan_bahan_baku_penunjangs',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // Check if columns don't already exist
                    if (!Schema::hasColumn($table->getTable(), 'is_audit')) {
                        $table->boolean('is_audit')->default(false)->after('uuid')->index()
                            ->comment('Flag untuk membedakan data operasional (false) vs data audit (true)');
                    }
                    
                    if (!Schema::hasColumn($table->getTable(), 'source_uuid')) {
                        $table->string('source_uuid')->nullable()->after('is_audit')->index()
                            ->comment('UUID dari data operasional asli yang disalin ke audit');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'is_audit')) {
                        $table->dropIndex(['is_audit']);
                        $table->dropColumn('is_audit');
                    }
                    
                    if (Schema::hasColumn($table->getTable(), 'source_uuid')) {
                        $table->dropIndex(['source_uuid']);
                        $table->dropColumn('source_uuid');
                    }
                });
            }
        }
    }
};
