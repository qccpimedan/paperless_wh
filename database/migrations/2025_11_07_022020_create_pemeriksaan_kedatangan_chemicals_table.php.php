<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_shift')->nullable()->constrained('shifts')->onDelete('set null');
            
            // Informasi Dasar
            $table->date('tanggal');
            $table->string('jenis_mobil')->nullable();
            $table->string('no_mobil')->nullable();
            $table->string('nama_supir')->nullable();
            $table->boolean('segel_gembok')->default(false);
            $table->string('no_segel')->nullable();
            
            // Kondisi Mobil (JSON - 11 items)
            $table->json('kondisi_mobil')->nullable();
            
            // Informasi Produk (Foreign Keys)
            $table->foreignId('id_chemical')->nullable()->constrained('chemicals')->onDelete('set null');
            $table->foreignId('id_produsen')->nullable()->constrained('produsens')->onDelete('set null');
            $table->string('negara_produsen')->nullable();
            $table->foreignId('id_distributor')->nullable()->constrained('distributors')->onDelete('set null');
            
            // Detail Pemeriksaan
            $table->string('kode_produksi')->nullable();
            $table->date('expire_date')->nullable();
            $table->enum('kondisi_chemical', ['Cair', 'Serbuk'])->nullable();
            $table->string('jumlah_datang')->nullable(); // kg/liter
            $table->string('jumlah_sampling')->nullable();
            
            // Kondisi Fisik (JSON - 2 items: kemasan, warna)
            $table->json('kondisi_fisik')->nullable();
            
           // Dokumen & Sertifikasi (2 items)
            $table->boolean('persyaratan_dokumen_halal')->default(false);  // Radio: Ya ✓ / Tidak ✗
            $table->boolean('coa')->default(false);                        // Radio: Ya ✓ / Tidak ✗
            
            // Hasil Pemeriksaan
            $table->enum('status', ['Release', 'Hold']);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemeriksaan_kedatangan_chemicals');
    }
};