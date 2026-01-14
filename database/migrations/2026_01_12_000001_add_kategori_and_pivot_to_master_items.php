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
        Schema::table('produks', function (Blueprint $table) {
            if (!Schema::hasColumn('produks', 'kategori_code')) {
                $table->string('kategori_code')->nullable()->after('nama_produk');
            }
        });

        Schema::table('bahans', function (Blueprint $table) {
            if (!Schema::hasColumn('bahans', 'kategori_code')) {
                $table->string('kategori_code')->nullable()->after('nama_bahan');
            }
        });

        Schema::table('bahan_kemasans', function (Blueprint $table) {
            if (!Schema::hasColumn('bahan_kemasans', 'kategori_code')) {
                $table->string('kategori_code')->nullable()->after('nama_kemasan');
            }
        });

        if (!Schema::hasTable('produk_produsen')) {
            Schema::create('produk_produsen', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_produk');
                $table->unsignedBigInteger('id_produsen');
                $table->unsignedBigInteger('id_plant');
                $table->timestamps();

                $table->unique(['id_plant', 'id_produk', 'id_produsen']);

                $table->foreign('id_produk')->references('id')->on('produks')->cascadeOnDelete();
                $table->foreign('id_produsen')->references('id')->on('produsens')->cascadeOnDelete();
                $table->foreign('id_plant')->references('id')->on('plants')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('produk_distributor')) {
            Schema::create('produk_distributor', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_produk');
                $table->unsignedBigInteger('id_distributor');
                $table->unsignedBigInteger('id_plant');
                $table->timestamps();

                $table->unique(['id_plant', 'id_produk', 'id_distributor']);

                $table->foreign('id_produk')->references('id')->on('produks')->cascadeOnDelete();
                $table->foreign('id_distributor')->references('id')->on('distributors')->cascadeOnDelete();
                $table->foreign('id_plant')->references('id')->on('plants')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('bahan_produsen')) {
            Schema::create('bahan_produsen', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_bahan');
                $table->unsignedBigInteger('id_produsen');
                $table->unsignedBigInteger('id_plant');
                $table->timestamps();

                $table->unique(['id_plant', 'id_bahan', 'id_produsen']);

                $table->foreign('id_bahan')->references('id')->on('bahans')->cascadeOnDelete();
                $table->foreign('id_produsen')->references('id')->on('produsens')->cascadeOnDelete();
                $table->foreign('id_plant')->references('id')->on('plants')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('bahan_distributor')) {
            Schema::create('bahan_distributor', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_bahan');
                $table->unsignedBigInteger('id_distributor');
                $table->unsignedBigInteger('id_plant');
                $table->timestamps();

                $table->unique(['id_plant', 'id_bahan', 'id_distributor']);

                $table->foreign('id_bahan')->references('id')->on('bahans')->cascadeOnDelete();
                $table->foreign('id_distributor')->references('id')->on('distributors')->cascadeOnDelete();
                $table->foreign('id_plant')->references('id')->on('plants')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('bahan_kemasan_produsen')) {
            Schema::create('bahan_kemasan_produsen', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_bahan_kemasan');
                $table->unsignedBigInteger('id_produsen');
                $table->unsignedBigInteger('id_plant');
                $table->timestamps();

                $table->unique(['id_plant', 'id_bahan_kemasan', 'id_produsen']);

                $table->foreign('id_bahan_kemasan')->references('id')->on('bahan_kemasans')->cascadeOnDelete();
                $table->foreign('id_produsen')->references('id')->on('produsens')->cascadeOnDelete();
                $table->foreign('id_plant')->references('id')->on('plants')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('bahan_kemasan_distributor')) {
            Schema::create('bahan_kemasan_distributor', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_bahan_kemasan');
                $table->unsignedBigInteger('id_distributor');
                $table->unsignedBigInteger('id_plant');
                $table->timestamps();

                $table->unique(['id_plant', 'id_bahan_kemasan', 'id_distributor']);

                $table->foreign('id_bahan_kemasan')->references('id')->on('bahan_kemasans')->cascadeOnDelete();
                $table->foreign('id_distributor')->references('id')->on('distributors')->cascadeOnDelete();
                $table->foreign('id_plant')->references('id')->on('plants')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_kemasan_distributor');
        Schema::dropIfExists('bahan_kemasan_produsen');
        Schema::dropIfExists('bahan_distributor');
        Schema::dropIfExists('bahan_produsen');
        Schema::dropIfExists('produk_distributor');
        Schema::dropIfExists('produk_produsen');

        Schema::table('bahan_kemasans', function (Blueprint $table) {
            if (Schema::hasColumn('bahan_kemasans', 'kategori_code')) {
                $table->dropColumn('kategori_code');
            }
        });

        Schema::table('bahans', function (Blueprint $table) {
            if (Schema::hasColumn('bahans', 'kategori_code')) {
                $table->dropColumn('kategori_code');
            }
        });

        Schema::table('produks', function (Blueprint $table) {
            if (Schema::hasColumn('produks', 'kategori_code')) {
                $table->dropColumn('kategori_code');
            }
        });
    }
};
