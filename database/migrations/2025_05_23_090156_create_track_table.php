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
        Schema::create('track', function (Blueprint $table) {
        $table->id();
        $table->string('no_asset');
        $table->string('username');
        $table->enum('status', ['digunakan', 'diperbaiki', 'dipindah', 'non aktif']);
        $table->text('keterangan');
        $table->date('tanggal_awal');
        $table->date('tanggal_ahir')->nullable();
        $table->timestamps();
        $table->foreign('no_asset')->references('no_asset')->on('barang')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track');
    }
};
