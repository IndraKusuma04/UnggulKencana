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
        Schema::create('pembelian_produk', function (Blueprint $table) {
            $table->id();
            $table->string('kodepembelianproduk', 100)->index();
            $table->string('kodeproduk', 100);
            $table->unsignedBigInteger('jenisproduk_id');
            $table->string('nama', 100);
            $table->integer('harga_beli')->default(0);
            $table->decimal('berat', 8, 3)->default(0.000);
            $table->integer('karat');
            $table->integer('lingkar')->default(0);
            $table->integer('panjang')->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('kondisi_id');
            $table->integer('status'); // 1 = aktif, 0 = rusak/kusam
            $table->unsignedBigInteger('oleh');
            $table->timestamps();

            $table->foreign('oleh')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kondisi_id')->references('id')->on('kondisi')->onDelete('cascade');
            $table->foreign('jenisproduk_id')->references('id')->on('jenis_produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_produk');
    }
};
