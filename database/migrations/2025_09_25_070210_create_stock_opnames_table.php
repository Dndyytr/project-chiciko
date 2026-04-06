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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_warehouses_id')->constrained('data_warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->morphs('material');
            $table->string('nama_gudang');
            $table->string('kode_item');
            $table->string('kode_barcode');
            $table->string('nama_item');
            $table->string('satuan');
            $table->integer('buku')->default(0);
            $table->integer('fisik')->default(0);
            $table->integer('selisih')->default(0);
            $table->timestamps();

            $table->foreign('satuan')->references('satuan')->on('list_unit_measure_estimates')->onDelete('cascade')->onUpdate('cascade');
            $table->index(['material_id', 'material_type', 'nama_gudang'], 'stock_opnames_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
