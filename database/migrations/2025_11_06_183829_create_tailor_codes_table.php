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
        Schema::create('tailor_codes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koordinator');
            $table->string('kode_koordinator');
            $table->string('nama_daerah');
            $table->string('kode_daerah');
            $table->string('nama_penjahit');
            $table->integer('no_urut')->default(0);
            $table->string('kode_penjahit')->unique();
            $table->timestamps();

            $table->foreign('kode_koordinator')->references('kode')->on('coordinator_codes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_daerah')->references('kode')->on('area_codes')->onDelete('cascade')->onUpdate('cascade');

            $table->index(['kode_koordinator', 'kode_daerah', 'kode_penjahit'], 'tc_idx');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tailor_codes');
    }
};
