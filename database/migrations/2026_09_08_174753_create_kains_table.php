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
        Schema::create('kains', function (Blueprint $table) {
            $table->id('id_kain');
            $table->string('kode_kain')->unique();
            $table->string('nama_kain');
            $table->string('jenis_kain')->nullable();
            $table->string('warna')->nullable();
            $table->decimal('stok', 8, 2)->default(0);
            $table->string('satuan')->default('Meter');
            $table->integer('harga_per_satuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kains');
    }
};
