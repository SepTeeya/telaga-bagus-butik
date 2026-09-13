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
        Schema::create('ukurans', function (Blueprint $table) {
            $table->id('id_ukuran');
            $table->foreignId('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade');
            $table->integer('P')->nullable();
            $table->integer('Bahu')->nullable();
            $table->integer('Dada')->nullable();
            $table->integer('Perut')->nullable();
            $table->integer('Pinggul')->nullable();
            $table->integer('PT')->nullable();
            $table->integer('LT')->nullable();
            $table->integer('Ketiak')->nullable();
            $table->integer('Leher')->nullable();
            $table->integer('bet_kebaya')->nullable();
            $table->integer('LP')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukurans');
    }
};
