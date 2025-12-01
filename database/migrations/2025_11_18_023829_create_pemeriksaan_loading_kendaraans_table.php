<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('id_ekspedisi');
            $table->unsignedBigInteger('id_kendaraan');
            $table->unsignedBigInteger('id_tujuan_pengiriman');
            $table->unsignedBigInteger('id_std_precooling');
            $table->unsignedBigInteger('id_user');
            $table->json('kondisi_kebersihan_mobil')->nullable();
            $table->json('kondisi_mobil')->nullable();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('suhu_precooling');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_ekspedisi')->references('id')->on('ekspedisis')->onDelete('cascade');
            $table->foreign('id_kendaraan')->references('id')->on('jenis_kendaraans')->onDelete('cascade');
            $table->foreign('id_tujuan_pengiriman')->references('id')->on('tujuan_pengirimen')->onDelete('cascade');
            $table->foreign('id_std_precooling')->references('id')->on('std_precoolings')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_loading_kendaraans');
    }
};