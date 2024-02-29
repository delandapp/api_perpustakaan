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
        Schema::create('detail_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('UserID');
            $table->string('NamaLengkap')->nullable(true);
            $table->string('NoTelepon')->nullable(true);
            $table->string('Alamat')->nullable(true);
            $table->timestamps();

            $table->foreign('UserID')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_users');
    }
};