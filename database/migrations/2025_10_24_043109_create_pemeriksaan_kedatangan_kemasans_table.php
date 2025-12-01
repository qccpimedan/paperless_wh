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
        Schema::create('pemeriksaan_kedatangan_kemasans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('tanggal');
            $table->string('jenis_mobil')->nullable();
            $table->string('no_mobil')->nullable();
            $table->string('nama_supir')->nullable();
            $table->boolean('segel_gembok')->default(false);
            $table->string('no_segel')->nullable();
            $table->string('jenis_pemeriksaan')->nullable();
            
            // Kondisi Mobil Pengangkut (JSON untuk 11 checklist)
            $table->json('kondisi_mobil')->nullable();
            
            $table->string('no_po')->nullable();
            $table->string('nama_bahan_kemasan')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->string('produsen')->nullable();
            $table->string('distributor')->nullable();
            $table->string('kode_produksi')->nullable();
            $table->string('jumlah_datang')->nullable();
            $table->string('jumlah_sampling')->nullable();
            
            // Kondisi Fisik (JSON untuk 4 checklist)
            $table->json('kondisi_fisik')->nullable();
            $table->decimal('ketebalan_micron', 8, 2)->nullable();
            
            $table->boolean('logo_halal')->default(false);
            $table->boolean('dokumen_halal')->default(false);
            $table->boolean('coa')->default(false);
            $table->enum('status', ['Release', 'Hold'])->default('Hold');
            $table->text('keterangan')->nullable();
            
            // Foreign Keys
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_shift')->nullable();
            $table->unsignedBigInteger('id_bahan')->nullable();
            
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_shift')->references('id')->on('shifts')->onDelete('set null');
            $table->foreign('id_bahan')->references('id')->on('bahans')->onDelete('set null');
            
            // Index for better performance
            $table->index(['tanggal', 'created_at']);
            $table->index('id_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_kedatangan_kemasans');
    }
};
