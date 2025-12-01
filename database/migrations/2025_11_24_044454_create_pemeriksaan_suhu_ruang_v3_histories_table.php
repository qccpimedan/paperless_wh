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
        Schema::create('pemeriksaan_suhu_ruang_v3_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_pemeriksaan_suhu_ruang_v3');
            $table->unsignedBigInteger('id_user');
            
            // 8 Suhu Fields - masing-masing dengan _lama dan _baru
            $table->json('suhu_premix_lama')->nullable();
            $table->json('suhu_premix_baru')->nullable();
            $table->json('suhu_seasoning_lama')->nullable();
            $table->json('suhu_seasoning_baru')->nullable();
            $table->json('suhu_dry_lama')->nullable();
            $table->json('suhu_dry_baru')->nullable();
            $table->json('suhu_cassing_lama')->nullable();
            $table->json('suhu_cassing_baru')->nullable();
            $table->json('suhu_beef_lama')->nullable();
            $table->json('suhu_beef_baru')->nullable();
            $table->json('suhu_packaging_lama')->nullable();
            $table->json('suhu_packaging_baru')->nullable();
            $table->json('suhu_ruang_chemical_lama')->nullable();
            $table->json('suhu_ruang_chemical_baru')->nullable();
            $table->json('suhu_ruang_seasoning_lama')->nullable();
            $table->json('suhu_ruang_seasoning_baru')->nullable();
            
            // Notes
            $table->text('keterangan_lama')->nullable();
            $table->text('keterangan_baru')->nullable();
            $table->text('tindakan_koreksi_lama')->nullable();
            $table->text('tindakan_koreksi_baru')->nullable();
            
            $table->timestamps();

            $table->foreign('id_pemeriksaan_suhu_ruang_v3')
                ->references('id')->on('pemeriksaan_suhu_ruang_v3s')
                ->onDelete('cascade')
                ->name('psrv3_histories_psrv3_fk');
            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->name('psrv3_histories_user_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_suhu_ruang_v3_histories');
    }
};