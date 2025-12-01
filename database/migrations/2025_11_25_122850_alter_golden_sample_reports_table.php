<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            // Hapus column collection_date
            $table->dropColumn('collection_date');
            
            // Tambah dua column baru
            $table->string('collection_date_from')->after('sample_type'); // Format: YYYY-MM
            $table->string('collection_date_to')->after('collection_date_from'); // Format: YYYY-MM
        });
    }

    public function down(): void {
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            // Hapus dua column baru
            $table->dropColumn(['collection_date_from', 'collection_date_to']);
            
            // Kembalikan column collection_date
            $table->date('collection_date')->after('sample_type');
        });
    }
};