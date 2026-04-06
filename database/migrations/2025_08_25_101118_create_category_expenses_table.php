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
        Schema::create('category_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->timestamps();

            $table->index('nama_kategori', 'category_expenses_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_categorys');
    }
};
