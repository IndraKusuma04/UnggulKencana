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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kodetransaksi', 100);
            $table->string('kodekeranjang_id', 100);
            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('diskon_id');
            $table->bigInteger('total');
            $table->date('tanggal');
            $table->unsignedBigInteger('oleh');
            $table->integer('status');
            $table->timestamps();

            $table->foreign('kodekeranjang_id')->references('kodekeranjang')->on('keranjang')->onDelete('cascade');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('cascade');
            $table->foreign('diskon_id')->references('id')->on('diskon')->onDelete('cascade');
            $table->foreign('oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
