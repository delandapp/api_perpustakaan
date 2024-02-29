<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategoribuku_relasis', function (Blueprint $table) {
            $table->id('KategoriRelasiID');
            $table->unsignedBigInteger('BukuID');
            $table->unsignedBigInteger('KategoriID');

            $table->foreign('BukuID')->references('BukuID')->on('bukus');
            $table->foreign('KategoriID')->references('KategoriID')->on('kategori_bukus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoribuku_relasis');
    }
};
