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
        Schema::create('pemeriksaan_suhu_ruang_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_pemeriksaan_suhu_ruang');
            $table->unsignedBigInteger('id_user');
            $table->json('suhu_data_lama')->nullable();
            $table->json('suhu_data_baru')->nullable();
            $table->text('keterangan_lama')->nullable();
            $table->text('keterangan_baru')->nullable();
            $table->text('tindakan_koreksi_lama')->nullable();
            $table->text('tindakan_koreksi_baru')->nullable();
            $table->timestamps();

            $table->foreign('id_pemeriksaan_suhu_ruang')
                ->references('id')->on('pemeriksaan_suhu_ruangs')
                ->onDelete('cascade')
                ->constrained()
                ->name('psr_histories_psr_fk');
            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->name('psr_histories_user_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_suhu_ruang_histories');
    }
};
