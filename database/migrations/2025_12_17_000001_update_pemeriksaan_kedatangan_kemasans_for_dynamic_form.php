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
        Schema::table('pemeriksaan_kedatangan_kemasans', function (Blueprint $table) {
            // Add columns to support dynamic form data
            // These columns will store JSON arrays for multiple rows
            
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'id_bahan_array')) {
                $table->json('id_bahan_array')->nullable()->after('id_bahan')->comment('Array of id_bahan for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'produsen_array')) {
                $table->json('produsen_array')->nullable()->after('produsen')->comment('Array of produsen for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'distributor_array')) {
                $table->json('distributor_array')->nullable()->after('distributor')->comment('Array of distributor for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'kode_produksi_array')) {
                $table->json('kode_produksi_array')->nullable()->after('kode_produksi')->comment('Array of kode_produksi for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'jumlah_datang_array')) {
                $table->json('jumlah_datang_array')->nullable()->after('jumlah_datang')->comment('Array of jumlah_datang for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'jumlah_sampling_array')) {
                $table->json('jumlah_sampling_array')->nullable()->after('jumlah_sampling')->comment('Array of jumlah_sampling for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'spesifikasi_array')) {
                $table->json('spesifikasi_array')->nullable()->after('spesifikasi')->comment('Array of spesifikasi for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'penampakan_array')) {
                $table->json('penampakan_array')->nullable()->after('kondisi_fisik')->comment('Array of penampakan for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'sealing_array')) {
                $table->json('sealing_array')->nullable()->after('penampakan_array')->comment('Array of sealing for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'cetakan_array')) {
                $table->json('cetakan_array')->nullable()->after('sealing_array')->comment('Array of cetakan for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'ketebalan_micron_array')) {
                $table->json('ketebalan_micron_array')->nullable()->after('ketebalan_micron')->comment('Array of ketebalan_micron for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'dimensi_array')) {
                $table->json('dimensi_array')->nullable()->after('dimensi')->comment('Array of dimensi for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'status_array')) {
                $table->json('status_array')->nullable()->after('status')->comment('Array of status for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'logo_halal_array')) {
                $table->json('logo_halal_array')->nullable()->after('logo_halal')->comment('Array of logo_halal for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'dokumen_halal_array')) {
                $table->json('dokumen_halal_array')->nullable()->after('dokumen_halal')->comment('Array of dokumen_halal for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'coa_array')) {
                $table->json('coa_array')->nullable()->after('coa')->comment('Array of coa for dynamic rows');
            }
            
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'keterangan_array')) {
                $table->json('keterangan_array')->nullable()->after('keterangan')->comment('Array of keterangan for dynamic rows');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_kemasans', function (Blueprint $table) {
            $table->dropColumn([
                'id_bahan_array',
                'produsen_array',
                'distributor_array',
                'kode_produksi_array',
                'jumlah_datang_array',
                'jumlah_sampling_array',
                'spesifikasi_array',
                'penampakan_array',
                'sealing_array',
                'cetakan_array',
                'ketebalan_micron_array',
                'dimensi_array',
                'status_array',
                'logo_halal_array',
                'dokumen_halal_array',
                'coa_array',
                'keterangan_array',
            ]);
        });
    }
};
