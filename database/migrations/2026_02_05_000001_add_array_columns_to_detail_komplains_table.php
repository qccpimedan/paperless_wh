<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_komplains', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_komplains', 'kategori_code_array')) {
                $table->json('kategori_code_array')->nullable()->after('no_po');
            }
            if (!Schema::hasColumn('detail_komplains', 'nama_produk_array')) {
                $table->json('nama_produk_array')->nullable()->after('nama_produk');
            }
            if (!Schema::hasColumn('detail_komplains', 'kode_produksi_array')) {
                $table->json('kode_produksi_array')->nullable()->after('kode_produksi');
            }
            if (!Schema::hasColumn('detail_komplains', 'expired_date_array')) {
                $table->json('expired_date_array')->nullable()->after('expired_date');
            }
            if (!Schema::hasColumn('detail_komplains', 'jumlah_datang_array')) {
                $table->json('jumlah_datang_array')->nullable()->after('jumlah_datang');
            }
            if (!Schema::hasColumn('detail_komplains', 'jumlah_di_tolak_array')) {
                $table->json('jumlah_di_tolak_array')->nullable()->after('jumlah_di_tolak');
            }
            if (!Schema::hasColumn('detail_komplains', 'dokumentasi_array')) {
                $table->json('dokumentasi_array')->nullable()->after('dokumentasi');
            }
            if (!Schema::hasColumn('detail_komplains', 'keterangan_array')) {
                $table->json('keterangan_array')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('detail_komplains', 'di_buat_oleh_array')) {
                $table->json('di_buat_oleh_array')->nullable()->after('di_buat_oleh');
            }
            if (!Schema::hasColumn('detail_komplains', 'setujui_oleh_array')) {
                $table->json('setujui_oleh_array')->nullable()->after('setujui_oleh');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_komplains', function (Blueprint $table) {
            $cols = [
                'kategori_code_array',
                'nama_produk_array',
                'kode_produksi_array',
                'expired_date_array',
                'jumlah_datang_array',
                'jumlah_di_tolak_array',
                'dokumentasi_array',
                'keterangan_array',
                'di_buat_oleh_array',
                'setujui_oleh_array',
            ];

            foreach ($cols as $col) {
                if (Schema::hasColumn('detail_komplains', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
