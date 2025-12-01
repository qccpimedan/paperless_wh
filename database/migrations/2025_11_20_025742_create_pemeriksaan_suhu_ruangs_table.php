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
        Schema::create('pemeriksaan_suhu_ruangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_shift');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_area');
            $table->date('tanggal');
            $table->json('suhu_data')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('tindakan_koreksi')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_shift')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('bahans')->onDelete('cascade');
            $table->foreign('id_area')->references('id')->on('input_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_suhu_ruangs');
    }
};
