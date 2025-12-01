<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_shift')->nullable()->constrained('shifts')->onDelete('set null');
            $table->foreignId('id_bahan')->nullable()->constrained('bahans')->onDelete('set null');
            
            // Informasi Dasar
            $table->date('tanggal');
            $table->string('jenis_mobil')->nullable();
            $table->string('no_mobil')->nullable();
            $table->string('nama_supir')->nullable();
            $table->boolean('segel_gembok')->default(false);
            $table->string('no_segel')->nullable();
            $table->string('jenis_pemeriksaan')->nullable();
            
            // Kondisi Mobil (JSON - 11 items)
            $table->json('kondisi_mobil')->nullable();
            
            // Informasi Produk
            $table->string('suhu_mobil')->nullable(); // String untuk suhu mobil
            $table->string('no_po')->nullable();
            $table->string('kondisi_produk')->nullable(); // Fresh/Frozen/Dry/Minyak
            $table->string('suhu_produk')->nullable(); // String untuk suhu produk
            
            // Detail Pemeriksaan
            $table->text('spesifikasi')->nullable();
            $table->string('produsen')->nullable();
            $table->string('negara_produsen')->nullable();
            $table->string('distributor')->nullable();
            $table->string('kode_produksi')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('jumlah_datang')->nullable(); // kg
            $table->string('jumlah_sampling')->nullable();
            
            // Kondisi Fisik (JSON - 4 items)
            $table->json('kondisi_fisik')->nullable();
            
            // Dokumen & Sertifikasi
            $table->boolean('logo_halal')->default(false);
            $table->string('hasil_uji_ffa')->nullable(); // Optional untuk minyak
            $table->boolean('dokumen_halal')->default(false);
            $table->boolean('coa')->default(false);
            $table->string('bukti_kebersihan_tanki')->nullable(); // Optional untuk minyak
            $table->boolean('sertifikat')->default(false);
            
            // Hasil Pemeriksaan
            $table->enum('status', ['Release', 'Hold']);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemeriksaan_kedatangan_bahan_baku_penunjangs');
    }
};