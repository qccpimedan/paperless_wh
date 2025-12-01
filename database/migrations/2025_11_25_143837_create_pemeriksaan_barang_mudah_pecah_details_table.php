<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_barang_mudah_pecah_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_input_area_locations');
            $table->integer('jumlah_barang');
            $table->enum('awal', ['ceklis', 'silang'])->nullable();
            $table->enum('akhir', ['ceklis', 'silang'])->nullable();
            $table->longText('temuan_ketidaksesuaian')->nullable();
            $table->longText('tindakan_koreksi')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pemeriksaan')
                ->references('id')
                ->on('pemeriksaan_barang_mudah_pecahs')
                ->onDelete('cascade');
            $table->foreign('id_barang')
                ->references('id')
                ->on('barangs')
                ->onDelete('cascade');
            $table->foreign('id_input_area_locations')
                ->references('id')
                ->on('input_area_locations')
                ->name('fk_detail_area_location')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_barang_mudah_pecah_details');
    }
};