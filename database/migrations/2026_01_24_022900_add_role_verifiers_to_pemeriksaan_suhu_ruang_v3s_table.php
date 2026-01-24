<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasQc = Schema::hasColumn('pemeriksaan_suhu_ruang_v3s', 'verified_by_qc');
        $hasProduksi = Schema::hasColumn('pemeriksaan_suhu_ruang_v3s', 'verified_by_produksi');
        $hasSpv = Schema::hasColumn('pemeriksaan_suhu_ruang_v3s', 'verified_by_spv');

        if (!$hasQc || !$hasProduksi || !$hasSpv) {
            Schema::table('pemeriksaan_suhu_ruang_v3s', function (Blueprint $table) use ($hasQc, $hasProduksi, $hasSpv) {
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

        Schema::table('pemeriksaan_suhu_ruang_v3s', function (Blueprint $table) use ($hasQc, $hasProduksi, $hasSpv) {
            if (!$hasQc) {
                $table->foreign('verified_by_qc')->references('id')->on('users')->nullOnDelete();
            }
            if (!$hasProduksi) {
                $table->foreign('verified_by_produksi')->references('id')->on('users')->nullOnDelete();
            }
            if (!$hasSpv) {
                $table->foreign('verified_by_spv')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        $hasQc = Schema::hasColumn('pemeriksaan_suhu_ruang_v3s', 'verified_by_qc');
        $hasProduksi = Schema::hasColumn('pemeriksaan_suhu_ruang_v3s', 'verified_by_produksi');
        $hasSpv = Schema::hasColumn('pemeriksaan_suhu_ruang_v3s', 'verified_by_spv');

        Schema::table('pemeriksaan_suhu_ruang_v3s', function (Blueprint $table) use ($hasQc, $hasProduksi, $hasSpv) {
            if ($hasQc) {
                $table->dropForeign(['verified_by_qc']);
            }
            if ($hasProduksi) {
                $table->dropForeign(['verified_by_produksi']);
            }
            if ($hasSpv) {
                $table->dropForeign(['verified_by_spv']);
            }
        });

        Schema::table('pemeriksaan_suhu_ruang_v3s', function (Blueprint $table) use ($hasQc, $hasProduksi, $hasSpv) {
            $drops = [];
            if ($hasQc) {
                $drops[] = 'verified_by_qc';
            }
            if ($hasProduksi) {
                $drops[] = 'verified_by_produksi';
            }
            if ($hasSpv) {
                $drops[] = 'verified_by_spv';
            }

            if (count($drops) > 0) {
                $table->dropColumn($drops);
            }
        });
    }
};
