<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('table_name');
            $table->string('action');
            $table->unsignedBigInteger('loggable_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();

            $table->index(['table_name', 'loggable_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};