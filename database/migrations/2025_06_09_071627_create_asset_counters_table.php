<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->unique()->constrained()->onDelete('cascade');
            $table->unsignedInteger('nomor_terakhir')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_counters');
    }
};
