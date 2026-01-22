<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'sent_to_produksi', 'approved_produksi', 'rejected_produksi', 'approved_spv', 'rejected_spv'])
                ->default('pending')
                ->after('keterangan_array');
            $table->unsignedBigInteger('verified_by')->nullable()->after('status_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_notes')->nullable()->after('verified_at');

            $table->unsignedBigInteger('verified_by_qc')->nullable()->after('verification_notes');
            $table->unsignedBigInteger('verified_by_produksi')->nullable()->after('verified_by_qc');
            $table->unsignedBigInteger('verified_by_spv')->nullable()->after('verified_by_produksi');

            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by_qc')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by_produksi')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by_spv')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['verified_by_qc']);
            $table->dropForeign(['verified_by_produksi']);
            $table->dropForeign(['verified_by_spv']);
            $table->dropColumn([
                'status_verifikasi',
                'verified_by',
                'verified_at',
                'verification_notes',
                'verified_by_qc',
                'verified_by_produksi',
                'verified_by_spv',
            ]);
        });
    }
};
