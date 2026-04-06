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
        Schema::create('purchase_based_notes', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_nota');
            $table->string('no_kwitansi');
            $table->string('kode_supplier');
            $table->string('nama_supplier');
            $table->integer('qty_roll')->default(0);
            $table->integer('qty_yard')->default(0);
            $table->decimal('jumlah', 20, 2);
            $table->timestamps();

            $table->foreign('kode_supplier')->references('kode')->on('list_supplier_estimates')->onDelete('cascade')->onUpdate('cascade');

            $table->index(['tanggal_nota', 'no_kwitansi', 'kode_supplier'], 'based_notes_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_based_notes');
    }
};
