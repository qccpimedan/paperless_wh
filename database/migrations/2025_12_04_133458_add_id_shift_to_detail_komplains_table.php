<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('detail_komplains', function (Blueprint $table) {
            $table->unsignedBigInteger('id_shift')->nullable()->after('id_user');
        });
    }
    
    public function down(): void {
        Schema::table('detail_komplains', function (Blueprint $table) {
            $table->dropColumn('id_shift');
        });
    }
};