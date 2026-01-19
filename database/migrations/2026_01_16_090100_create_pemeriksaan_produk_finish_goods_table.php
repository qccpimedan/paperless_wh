<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_shift')->nullable()->constrained('shifts')->onDelete('set null');

            // Informasi Dasar
            $table->date('tanggal');
            $table->string('jenis_mobil')->nullable();
            $table->string('no_mobil')->nullable();
            $table->string('nama_supir')->nullable();
            $table->string('segel_gembok', 10)->nullable();
            $table->string('no_segel')->nullable();

            // Kondisi Mobil (JSON)
            $table->json('kondisi_mobil')->nullable();

            // Suhu & Kondisi Produk (Header)
            $table->string('suhu_mobil')->nullable(); // Frozen/Fresh
            $table->string('suhu_produk')->nullable(); // Frozen/Fresh
            $table->string('kondisi_produk')->nullable(); // Frozen/Fresh/Dry

            // Dynamic rows (JSON arrays)
            $table->json('kategori_code_array')->nullable();
            $table->json('id_produk_array')->nullable();
            $table->json('produsen_array')->nullable();
            $table->json('negara_produsen_array')->nullable();
            $table->json('distributor_array')->nullable();
            $table->json('kode_produksi_array')->nullable();
            $table->json('expire_date_array')->nullable();
            $table->json('jumlah_datang_array')->nullable();
            $table->json('jumlah_sampling_array')->nullable();

            $table->json('kondisi_kemasan_array')->nullable();
            $table->json('kondisi_warna_array')->nullable();
            $table->json('kondisi_aroma_array')->nullable();

            $table->json('logo_halal_array')->nullable();
            $table->json('dokumen_halal_array')->nullable();
            $table->json('coa_array')->nullable();

            $table->json('status_array')->nullable();
            $table->json('keterangan_array')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_produk_finish_goods');
    }
};
