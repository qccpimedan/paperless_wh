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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->string('username')->unique()->after('name');
            $table->unsignedBigInteger('id_role')->nullable()->after('email');
            $table->unsignedBigInteger('id_plant')->nullable()->after('id_role');
            
            // Foreign key constraints
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('id_plant')->references('id')->on('plants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_role']);
            $table->dropForeign(['id_plant']);
            $table->dropColumn(['uuid', 'username', 'id_role', 'id_plant']);
        });
    }
};
