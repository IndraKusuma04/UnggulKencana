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
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->string('kodekeranjang', 100)->index();
            $table->unsignedBigInteger('produk_id');
            $table->integer('total');
            $table->unsignedBigInteger('oleh');
            $table->integer('status');
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->foreign('oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
