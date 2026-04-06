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
        Schema::create('list_supplier_estimates', function (Blueprint $table) {
            $table->id();
            $table->string('inisial')->unique();
            $table->string('nama_supplier');
            $table->text('alamat');
            $table->string('kontak');
            $table->string('rekening');
            $table->string('kode')->unique();
            $table->timestamps();

            $table->index(['inisial', 'nama_supplier', 'kode'], 'supplier_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_supplier_estimates');
    }
};
