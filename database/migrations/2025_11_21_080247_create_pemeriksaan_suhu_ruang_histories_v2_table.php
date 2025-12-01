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
        Schema::create('pemeriksaan_suhu_ruang_v2_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_pemeriksaan_suhu_ruang_v2');
            $table->unsignedBigInteger('id_user');
            $table->json('suhu_cold_storage_lama')->nullable();
            $table->json('suhu_cold_storage_baru')->nullable();
            $table->json('suhu_anteroom_loading_lama')->nullable();
            $table->json('suhu_anteroom_loading_baru')->nullable();
            $table->json('suhu_pre_loading_lama')->nullable();
            $table->json('suhu_pre_loading_baru')->nullable();
            $table->json('suhu_prestaging_lama')->nullable();
            $table->json('suhu_prestaging_baru')->nullable();
            $table->json('suhu_anteroom_ekspansi_abf_lama')->nullable();
            $table->json('suhu_anteroom_ekspansi_abf_baru')->nullable();
            $table->json('suhu_chillroom_rm_lama')->nullable();
            $table->json('suhu_chillroom_rm_baru')->nullable();
            $table->json('suhu_chillroom_domestik_lama')->nullable();
            $table->json('suhu_chillroom_domestik_baru')->nullable();
            $table->text('keterangan_lama')->nullable();
            $table->text('keterangan_baru')->nullable();
            $table->text('tindakan_koreksi_lama')->nullable();
            $table->text('tindakan_koreksi_baru')->nullable();
            $table->timestamps();

            $table->foreign('id_pemeriksaan_suhu_ruang_v2')
                ->references('id')->on('pemeriksaan_suhu_ruang_v2s')
                ->onDelete('cascade')
                ->name('psrv2_histories_psrv2_fk');
            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->name('psrv2_histories_user_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_suhu_ruang_v2_histories');
    }
};
