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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id('BukuID');
            $table->string('Judul');
            $table->string('Deskripsi');
            $table->string('Penulis');
            $table->string('Penerbit');
            $table->string('TahunTerbit');
            $table->string('JumlahHalaman');
            $table->string('CoverBuku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};