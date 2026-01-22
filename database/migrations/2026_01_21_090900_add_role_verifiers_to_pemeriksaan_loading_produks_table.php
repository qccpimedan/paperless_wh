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
        Schema::table('pemeriksaan_loading_produks', function (Blueprint $table) {
            $table->unsignedBigInteger('verified_by_qc')->nullable()->after('verified_by');
            $table->unsignedBigInteger('verified_by_produksi')->nullable()->after('verified_by_qc');
            $table->unsignedBigInteger('verified_by_spv')->nullable()->after('verified_by_produksi');

            $table->foreign('verified_by_qc')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by_produksi')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by_spv')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_loading_produks', function (Blueprint $table) {
            $table->dropForeign(['verified_by_qc']);
            $table->dropForeign(['verified_by_produksi']);
            $table->dropForeign(['verified_by_spv']);
            $table->dropColumn(['verified_by_qc', 'verified_by_produksi', 'verified_by_spv']);
        });
    }
};
