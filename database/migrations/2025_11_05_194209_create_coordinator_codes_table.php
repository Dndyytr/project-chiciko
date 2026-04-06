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
        Schema::create('coordinator_codes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koordinator');
            $table->string('kode')->unique();
            $table->timestamps();

            $table->index('kode', 'cc_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordinator_codes');
    }
};
