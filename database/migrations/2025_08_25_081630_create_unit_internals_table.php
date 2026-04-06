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
        Schema::create('unit_internals', function (Blueprint $table) {
            $table->id();
            $table->integer('nilai');
            $table->string('satuan_ukur');
            $table->timestamps();

            $table->index(['satuan_ukur']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_internals');
    }
};
