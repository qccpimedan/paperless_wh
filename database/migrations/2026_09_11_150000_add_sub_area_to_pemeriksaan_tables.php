<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * List of tables to add sub_area column.
     */
    protected array $tables = [
        'pemeriksaan_suhu_ruangs',
        'pemeriksaan_suhu_ruang_v2s',
        'pemeriksaan_suhu_ruang_v3s',
        'pemeriksaan_kebersihan_areas',
        'pemeriksaan_loading_produks',
        'pemeriksaan_loading_kendaraans',
        'pemeriksaan_kedatangan_bahan_baku_penunjangs',
        'pemeriksaan_kedatangan_chemicals',
        'pemeriksaan_kedatangan_kemasans',
        'pemeriksaan_produk_finish_goods',
        'pemeriksaan_return_barang_customers',
        'golden_sample_reports',
        'detail_komplains',
        'pemeriksaan_barang_mudah_pecahs',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'sub_area')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('sub_area', 100)->nullable()->after('id_user')->index();
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
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'sub_area')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('sub_area');
                });
            }
        }
    }
};
