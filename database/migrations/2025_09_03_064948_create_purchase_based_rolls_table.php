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
        Schema::create('purchase_based_rolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_raw_materials_id');
            $table->string('kode_barcode');
            $table->string('nama_barang');
            $table->string('jenis_kain');
            $table->string('warna');
            $table->integer('qty_roll')->default(0);
            $table->decimal('yard_per_roll', 20, 2);
            $table->decimal('kg_per_roll', 20, 2);
            $table->integer('jumlah_roll_satuan')->default(0);
            $table->decimal('total_harga', 20, 2);
            $table->decimal('harga_per_satuan', 20, 2);
            $table->timestamps();

            $table->foreign('incoming_raw_materials_id', 'pbr_irm_fk')
                ->references('id')
                ->on('incoming_raw_materials')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_based_rolls');
    }
};
