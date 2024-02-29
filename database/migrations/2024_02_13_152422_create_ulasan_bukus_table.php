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
        Schema::create('ulasan_bukus', function (Blueprint $table) {
            $table->id('UlasanID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->string('Ulasan');
            $table->integer('Rating');
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
        Schema::dropIfExists('ulasan_bukus');
    }
};