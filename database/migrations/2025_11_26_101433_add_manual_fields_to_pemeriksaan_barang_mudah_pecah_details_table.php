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
        Schema::table('pemeriksaan_barang_mudah_pecah_details', function (Blueprint $table) {
            $table->string('nama_barang_manual')->nullable()->after('id_barang');
            $table->string('nama_karyawan')->nullable()->after('tindakan_koreksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_barang_mudah_pecah_details', function (Blueprint $table) {
            $table->dropColumn(['nama_barang_manual', 'nama_karyawan']);
        });
    }
};