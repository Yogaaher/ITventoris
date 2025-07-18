<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mutasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->string('no_asset_lama');
            $table->foreignId('perusahaan_lama_id')->constrained('perusahaans')->onDelete('cascade');
            $table->string('pengguna_lama');
            $table->string('no_asset_baru');
            $table->foreignId('perusahaan_baru_id')->constrained('perusahaans')->onDelete('cascade');
            $table->string('pengguna_baru');
            $table->date('tanggal_mutasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};
