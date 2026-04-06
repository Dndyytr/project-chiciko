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
        Schema::create('incoming_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_nota');
            $table->string('no_kwitansi');
            $table->string('kode_supplier');
            $table->string('nama_supplier');
            $table->string('kode_barcode');
            $table->string('satuan_ukur');
            $table->string('nama_barang');
            $table->string('jenis_kain');
            $table->string('warna');
            $table->integer('yard')->default(0);
            $table->text('nama_barang_detail');
            $table->integer('qty_roll')->default(0);
            $table->decimal('kg_roll', 20, 2);
            $table->integer('jumlah_roll_satuan')->default(0);
            $table->decimal('harga_per_satuan', 20, 2);
            $table->decimal('harga_awal', 20, 2);
            $table->decimal('nominal_diskon', 20, 2);
            $table->decimal('total_diskon', 20, 2);
            $table->decimal('total_harga', 20, 2);
            $table->timestamps();

            $table->foreign('kode_supplier')->references('kode')->on('list_supplier_estimates')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('satuan_ukur')->references('satuan')->on('list_unit_measure_estimates')->onDelete('cascade')->onUpdate('cascade');

            $table->index(
                ['tanggal_nota', 'no_kwitansi', 'kode_supplier', 'nama_supplier'],
                'incoming_raw_idx' // nama index custom, pendek
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_raw_materials');
    }
};
