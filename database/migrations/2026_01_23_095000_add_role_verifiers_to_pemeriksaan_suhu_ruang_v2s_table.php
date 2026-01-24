<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasQc = Schema::hasColumn('pemeriksaan_suhu_ruang_v2s', 'verified_by_qc');
        $hasProduksi = Schema::hasColumn('pemeriksaan_suhu_ruang_v2s', 'verified_by_produksi');
        $hasSpv = Schema::hasColumn('pemeriksaan_suhu_ruang_v2s', 'verified_by_spv');

        if (!$hasQc || !$hasProduksi || !$hasSpv) {
            Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) use ($hasQc, $hasProduksi, $hasSpv) {
                if (!$hasQc) {
                    $table->unsignedBigInteger('verified_by_qc')->nullable()->after('verification_notes');
                }
                if (!$hasProduksi) {
                    $table->unsignedBigInteger('verified_by_produksi')->nullable()->after('verified_by_qc');
                }
                if (!$hasSpv) {
                    $table->unsignedBigInteger('verified_by_spv')->nullable()->after('verified_by_produksi');
                }
            });
        }

        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            try {
                $table->foreign('verified_by_qc')->references('id')->on('users')->nullOnDelete();
            } catch (Throwable $e) {
            }
            try {
                $table->foreign('verified_by_produksi')->references('id')->on('users')->nullOnDelete();
            } catch (Throwable $e) {
            }
            try {
                $table->foreign('verified_by_spv')->references('id')->on('users')->nullOnDelete();
            } catch (Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            try {
                $table->dropForeign(['verified_by_qc']);
            } catch (Throwable $e) {
            }
            try {
                $table->dropForeign(['verified_by_produksi']);
            } catch (Throwable $e) {
            }
            try {
                $table->dropForeign(['verified_by_spv']);
            } catch (Throwable $e) {
            }

            if (Schema::hasColumn('pemeriksaan_suhu_ruang_v2s', 'verified_by_qc')) {
                $table->dropColumn('verified_by_qc');
            }
            if (Schema::hasColumn('pemeriksaan_suhu_ruang_v2s', 'verified_by_produksi')) {
                $table->dropColumn('verified_by_produksi');
            }
            if (Schema::hasColumn('pemeriksaan_suhu_ruang_v2s', 'verified_by_spv')) {
                $table->dropColumn('verified_by_spv');
            }
        });
    }
};
