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
        Schema::create('list_color_estimates', function (Blueprint $table) {
            $table->id();
            $table->string('warna');
            $table->string('kode')->unique();
            $table->timestamps();

            $table->index(['warna', 'kode'], 'color_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_color_estimates');
    }
};
