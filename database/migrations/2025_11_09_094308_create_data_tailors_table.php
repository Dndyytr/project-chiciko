<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_tailors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koordinator');
            $table->string('kode_koordinator');
            $table->string('nama_penjahit');
            $table->string('nama_daerah');
            $table->string('kode_penjahit');
            $table->timestamps();

            $table->foreign('kode_penjahit')->references('kode_penjahit')->on('tailor_codes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_koordinator')->references('kode')->on('coordinator_codes')->onDelete('cascade')->onUpdate('cascade');

            $table->index(['kode_penjahit', 'kode_koordinator'], 'dt_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_tailors');
    }
};
