<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_komplains', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama_supplier');
            $table->date('tanggal_kedatangan');
            $table->string('no_po');
            $table->string('nama_produk');
            $table->string('kode_produksi');
            $table->date('expired_date');
            $table->string('jumlah_datang'); // String, bukan decimal
            $table->string('jumlah_di_tolak'); // String, bukan decimal
            $table->string('dokumentasi')->nullable();
            $table->string('upload_suplier')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('di_buat_oleh')->nullable();
            $table->string('setujui_oleh')->nullable();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_komplains');
    }
};