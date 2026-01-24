<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            // drop FK lama (psr_v2_produk_fk) kalau ada
            try {
                $table->dropForeign('psr_v2_produk_fk');
            } catch (Throwable $e) {
                // ignore
            }
        });

        // Set NULL jika ada id_produk yang tidak ada di tabel produks (menghindari gagal saat add FK)
        DB::statement("UPDATE pemeriksaan_suhu_ruang_v2s v LEFT JOIN produks p ON p.id = v.id_produk SET v.id_produk = NULL WHERE p.id IS NULL");

        // Pastikan kolom id_produk nullable (tanpa doctrine/dbal)
        DB::statement('ALTER TABLE pemeriksaan_suhu_ruang_v2s MODIFY id_produk BIGINT UNSIGNED NULL');

        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            $table->foreign('id_produk', 'psr_v2_produk_fk')
                ->references('id')
                ->on('produks')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            try {
                $table->dropForeign('psr_v2_produk_fk');
            } catch (Throwable $e) {
                // ignore
            }
        });

        // Tetap nullable agar rollback tidak gagal karena data lama yang tidak match.
        DB::statement('ALTER TABLE pemeriksaan_suhu_ruang_v2s MODIFY id_produk BIGINT UNSIGNED NULL');

        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            $table->foreign('id_produk', 'psr_v2_produk_fk')
                ->references('id')
                ->on('bahans')
                ->nullOnDelete();
        });
    }
};
