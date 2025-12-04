<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'sent_to_produksi', 'approved_produksi', 'rejected_produksi', 'approved_spv', 'rejected_spv'])
                  ->default('pending')
                  ->after('keterangan');
            $table->unsignedBigInteger('verified_by')->nullable()->after('status_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_notes')->nullable()->after('verified_at');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['status_verifikasi', 'verified_by', 'verified_at', 'verification_notes']);
        });
    }
};