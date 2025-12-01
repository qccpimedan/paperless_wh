<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_return_barang_customers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_shift')->nullable();
            $table->date('tanggal');
            $table->unsignedBigInteger('id_ekspedisi')->nullable();
            $table->string('no_polisi');
            $table->string('nama_supir');
            $table->time('waktu_kedatangan');
            $table->string('suhu_mobil');
            $table->unsignedBigInteger('id_customer');
            $table->string('alasan_return');
            $table->enum('kondisi_produk', ['Frozen', 'Fresh', 'Dry']);
            $table->unsignedBigInteger('id_produk');
            $table->string('suhu_produk')->nullable();
            $table->string('kode_produksi');
            $table->date('expired_date');
            $table->string('jumlah_barang');
            $table->boolean('kondisi_kemasan');
            $table->boolean('kondisi_produk_check');
            $table->string('rekomendasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_shift')->references('id')->on('shifts')->onDelete('set null');
            $table->foreign('id_ekspedisi')->references('id')->on('ekspedisis')->onDelete('set null');
            $table->foreign('id_customer')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('produks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_return_barang_customers');
    }
};