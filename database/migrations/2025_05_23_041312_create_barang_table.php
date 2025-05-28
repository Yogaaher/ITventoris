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
        $table->enum('perusahaan', ['SCO', 'SCT', 'SCP', 'Migen']);
        $table->enum('jenis_barang', ['Laptop', 'HP', 'PC/AIO', 'Printer', 'Proyektor', 'Others']);
        $table->string('no_asset');
        $table->string('merek');
        $table->date('tgl_pengadaan');
        $table->string('serial_number')->unique();
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
