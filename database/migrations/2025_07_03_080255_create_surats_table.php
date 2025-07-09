<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->unique();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->string('nama_penerima');
            $table->string('nik_penerima');
            $table->string('jabatan_penerima');
            $table->string('nama_pemberi');
            $table->string('nik_pemberi');
            $table->string('jabatan_pemberi');
            $table->string('penanggung_jawab');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};

