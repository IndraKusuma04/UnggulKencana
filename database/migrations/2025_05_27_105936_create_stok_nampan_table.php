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
        Schema::create('stok_nampan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nampan_id');
            $table->date('tanggal');
            $table->integer('stokprodukawal')->default(0);
            $table->integer('stokprodukakhir')->default(0);
            $table->decimal('stokawalberat', 13, 5)->default(0.0);
            $table->decimal('stokakhirberat', 13, 5)->default(0.0);
            $table->integer('status');
            $table->timestamps();

            $table->foreign('nampan_id')->references('id')->on('nampan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_nampan');
    }
};
