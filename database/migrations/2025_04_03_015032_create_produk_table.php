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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('kodeproduk', 100);
            $table->unsignedBigInteger('jenisproduk_id');
            $table->string('nama', 100);
            $table->integer('harga_jual')->default(0);
            $table->integer('harga_beli')->default(0);
            $table->decimal('berat', 8, 3)->nullable()->default(0.000);
            $table->integer('karat');
            $table->integer('lingkar')->default(0);
            $table->integer('panjang')->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('kondisi_id');
            $table->string('image_produk', 100)->nullable()->default('notfound.png');
            $table->integer('status');
            $table->timestamps();

            $table->foreign('jenisproduk_id')->references('id')->on('jenis_produk')->onDelete('cascade');
            $table->foreign('kondisi_id')->references('id')->on('kondisi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
