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
        Schema::create('barang', function (Blueprint $table) {
        $table->id();
        $table->foreignId('perusahaan_id')->constrained('perusahaans');
        $table->foreignId('jenis_barang_id')->constrained('jenis_barangs');
        $table->string('no_asset')->unique();
        $table->string('merek');
        $table->date('tgl_pengadaan');
        $table->string('serial_number')->unique();
        $table->enum('status', ['Digunakan', 'Diperbaiki', 'Dipindah', 'Non Aktif', 'Tersedia'])->default('Tersedia'); 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
