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
        Schema::create('list_unit_measure_estimates', function (Blueprint $table) {
            $table->id();
            $table->string('satuan')->unique();
            $table->string('arti')->unique();
            $table->string('kode')->unique();
            $table->timestamps();

            $table->index(['satuan', 'arti', 'kode'], 'unit_measure_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_unit_measure_estimates');
    }
};
