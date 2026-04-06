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
        Schema::create('worksheet_abbreviations', function (Blueprint $table) {
            $table->id();
            $table->string('singkatan');
            $table->string('lengkap');
            $table->timestamps();

            $table->index(['singkatan', 'lengkap'], 'worksheet_abbreviations_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksheet_abbreviations');
    }
};
