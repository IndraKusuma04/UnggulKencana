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
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id();
            $table->string('kodeperbaikan', 100);
            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('kondisi_id');
            $table->text('keterangan');
            $table->date('tanggalmasuk');
            $table->date('tanggalkeluar')->nullable();
            $table->unsignedBigInteger('oleh');
            $table->integer('status');
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->foreign('kondisi_id')->references('id')->on('kondisi')->onDelete('cascade');
            $table->foreign('oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};
