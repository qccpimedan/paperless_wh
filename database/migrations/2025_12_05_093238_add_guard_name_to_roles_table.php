<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Add guard_name column if it doesn't exist
            if (!Schema::hasColumn('roles', 'guard_name')) {
                $table->string('guard_name')->default('web')->after('role');
            }
            
            // Add name column if it doesn't exist (for Spatie)
            if (!Schema::hasColumn('roles', 'name')) {
                $table->string('name')->nullable()->after('guard_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['guard_name', 'name']);
        });
    }
};