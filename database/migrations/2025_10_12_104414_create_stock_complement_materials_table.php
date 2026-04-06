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
        Schema::create('stock_complement_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_complement_materials_id');
            $table->foreignId('stock_opnames_id')->nullable();
            $table->text('nama_item');
            $table->integer('barang_keluar')->default(0);
            $table->integer('stock_akhir')->default(0);
            $table->decimal('harga_barang_keluar', 20, 2);
            $table->decimal('total_harga_stock_akhir', 20, 2);
            $table->decimal('harga_satuan_stock_akhir', 20, 2);
            $table->timestamps();

            $table->index(['barang_keluar', 'stock_akhir'], 'stock_cm_idx');

            // Relasi ke incoming_complement_materials
            $table->foreign('incoming_complement_materials_id', 'scm_icm_fk')
                ->references('id')
                ->on('incoming_complement_materials')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Relasi ke stock_opnames
            $table->foreign('stock_opnames_id', 'scm_so_fk')
                ->references('id')
                ->on('stock_opnames')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_complement_materials');
    }
};
