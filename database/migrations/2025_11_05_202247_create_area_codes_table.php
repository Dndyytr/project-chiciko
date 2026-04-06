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
        Schema::create('area_codes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_daerah');
            $table->string('kode')->unique();
            $table->timestamps();

            $table->index('kode', 'ac_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_codes');
    }
};
