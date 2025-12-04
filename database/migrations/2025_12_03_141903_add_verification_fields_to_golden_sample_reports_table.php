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
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            // Status verifikasi: pending, sent_to_produksi, approved_produksi, rejected_produksi, approved_spv, rejected_spv
            $table->enum('status_verifikasi', ['pending', 'sent_to_produksi', 'approved_produksi', 'rejected_produksi', 'approved_spv', 'rejected_spv'])
                  ->default('pending')
                  ->after('samples');
            
            // User yang melakukan verifikasi terakhir
            $table->unsignedBigInteger('verified_by')->nullable()->after('status_verifikasi');
            
            // Timestamp verifikasi
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            
            // Catatan/alasan penolakan
            $table->text('verification_notes')->nullable()->after('verified_at');
            
            // Foreign key
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['status_verifikasi', 'verified_by', 'verified_at', 'verification_notes']);
        });
    }
};
