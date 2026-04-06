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
        Schema::create('data_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_nota');
            $table->string('no_nota');
            $table->string('kategori');
            $table->text('keterangan');
            $table->decimal('harga_satuan', 20, 2);
            $table->integer('kuantitas')->default(0);
            $table->decimal('kredit', 20, 2);
            $table->timestamps();

            $table->foreign('kategori')->references('nama_kategori')->on('category_expenses')->onDelete('cascade')->onUpdate('cascade');
            $table->index(['tanggal_nota', 'no_nota', 'kategori', 'kredit'], 'data_expenses_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_expenses');
    }
};
