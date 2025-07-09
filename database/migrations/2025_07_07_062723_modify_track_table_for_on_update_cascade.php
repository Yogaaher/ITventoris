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
        Schema::table('track', function (Blueprint $table) {
            $table->dropForeign('track_serial_number_foreign');
            $table->foreign('serial_number')
                ->references('serial_number')
                ->on('barang')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('track', function (Blueprint $table) {
            $table->dropForeign(['serial_number']);

            $table->foreign('serial_number')
                ->references('serial_number')
                ->on('barang')
                ->onDelete('cascade');
        });
    }
};
