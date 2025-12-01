<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemeriksaan_loading_produks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_shift')->nullable()->constrained('shifts')->onDelete('set null');
            
            // Informasi Dasar
            $table->date('tanggal');
            $table->foreignId('id_tujuan_pengiriman')->nullable()->constrained('tujuan_pengirimen')->onDelete('set null');
            $table->foreignId('id_kendaraan')->nullable()->constrained('jenis_kendaraans')->onDelete('set null');
            $table->foreignId('id_supir')->nullable()->constrained('supirs')->onDelete('set null');
            
            // Waktu Loading
            $table->time('star_loading')->nullable();
            $table->time('selesai_loading')->nullable();
            
            // Temperature
            $table->string('temperature_mobil')->nullable();
            $table->json('temperature_produk')->nullable(); // Array untuk multiple pengecekan
            
            // Kondisi Produk
            $table->enum('kondisi_produk', ['Frozen', 'Fresh', 'Dry'])->nullable();
            $table->boolean('segel_gembok')->default(false);
            $table->string('no_segel')->nullable();
            
            // Informasi PO & Produk
            $table->string('no_po')->nullable();
            $table->foreignId('id_produk')->nullable()->constrained('produks')->onDelete('set null');
            $table->string('kode_produksi')->nullable();
            $table->date('best_before')->nullable();
            
            // Jumlah
            $table->string('jumlah_kemasan')->nullable();
            $table->string('jumlah_sampling')->nullable();
            
            // Kondisi & Keterangan
            $table->boolean('kondisi_kemasan')->default(true);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemeriksaan_loading_produks');
    }
};