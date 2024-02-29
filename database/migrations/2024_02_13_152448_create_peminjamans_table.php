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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('PeminjamanID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->string('KodePeminjaman');
            $table->date('TanggalPinjam');
            $table->date('Deadline');
            $table->date('TanggalKembali')->nullable(true);
            $table->enum('Status', ['Disetujui', 'Ditolak', 'Dipinjam', 'Selesai', 'Proses'])->default(null);
            $table->timestamps();
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('BukuID')->references('BukuID')->on('bukus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
