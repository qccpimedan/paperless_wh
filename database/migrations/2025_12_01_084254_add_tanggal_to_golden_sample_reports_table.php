<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('collection_date_to');
        });
    }

    public function down(): void {
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
};