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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('kodepembelian', 100)->unique(); // kode transaksi pembelian utama
            $table->string('kodepembelianproduk', 100); // untuk menghubungkan dengan pembelian_produk (keranjang)
            $table->unsignedBigInteger('suplier_id')->nullable();   // jika dari suplier
            $table->unsignedBigInteger('pelanggan_id')->nullable(); // jika dari pelanggan
            $table->string('nonsuplierdanpembeli', 100)->nullable(); // jika input manual
            $table->date('tanggal');
            $table->integer('total_harga')->default(0);
            $table->integer('diskon')->default(0);
            $table->integer('grand_total')->default(0);
            $table->unsignedBigInteger('oleh'); // user yang menginput
            $table->text('catatan')->nullable(); // opsional
            $table->integer('status')->default(1); // misal: 1 = selesai, 0 = draft, dll
            $table->timestamps();

            // Relasi
            $table->foreign('suplier_id')->references('id')->on('suplier')->onDelete('set null');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('set null');
            $table->foreign('kodepembelianproduk')->references('kodepembelianproduk')->on('pembelian_produk')->onDelete('cascade');
            $table->foreign('oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
