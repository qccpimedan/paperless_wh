<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_shift');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_area');
            $table->date('tanggal');
            
            // Area Finish Good, Meat, Dry Warehouse
            $table->string('area_finish_good')->nullable();
            
            // Suhu Cold Storage
            $table->json('suhu_cold_storage')->nullable();
            
            // Suhu Anteroom Loading
            $table->json('suhu_anteroom_loading')->nullable();
            
            // Suhu Pre Loading
            $table->json('suhu_pre_loading')->nullable();
            
            // Suhu Prestaging
            $table->json('suhu_prestaging')->nullable();
            
            // Suhu Anteroom Ekspansi ABF
            $table->json('suhu_anteroom_ekspansi_abf')->nullable();
            
            // Suhu Chillroom RM
            $table->json('suhu_chillroom_rm')->nullable();
            
            // Suhu Chillroom Domestik
            $table->json('suhu_chillroom_domestik')->nullable();
            
            // Keterangan dan Tindakan
            $table->text('keterangan')->nullable();
            $table->text('tindakan_koreksi')->nullable();
            
            // Dibuat Oleh, Disetujui Oleh
            $table->string('dibuat_oleh')->nullable();
            $table->string('disetujui_oleh')->nullable();
            
            $table->timestamps();
            
            $table->foreign('id_user', 'psr_v2_user_fk')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('id_shift', 'psr_v2_shift_fk')
                ->references('id')->on('shifts')
                ->onDelete('cascade');
            $table->foreign('id_produk', 'psr_v2_produk_fk')
                ->references('id')->on('bahans')
                ->onDelete('cascade');
            $table->foreign('id_area', 'psr_v2_area_fk')
                ->references('id')->on('input_areas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_suhu_ruang_v2s');
    }
};