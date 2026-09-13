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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
            $table->foreignId('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('bahan');
        $table->string('model_baju');
            $table->date('tgl_pesan');
            $table->date('tgl_deadline');
            $table->string('status_pesanan')->default('Belum Diproses');
            $table->integer('dp')->nullable();
            $table->integer('sisa_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
