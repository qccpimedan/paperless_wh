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
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            $table->string('masa_penyimpanan')->nullable()->after('sample_type');
            $table->dropColumn(['collection_date_from', 'collection_date_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('golden_sample_reports', function (Blueprint $table) {
            $table->string('collection_date_from')->nullable()->after('sample_type');
            $table->string('collection_date_to')->nullable()->after('collection_date_from');
            $table->dropColumn('masa_penyimpanan');
        });
    }
};
