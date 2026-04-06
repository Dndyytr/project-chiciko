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
        Schema::create('complement_based_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complement_materials_id')->constrained('incoming_complement_materials')->onDelete('cascade')->onUpdate('cascade');
            $table->text('nama_barang_sesuai_nota');
            $table->integer('jumlah_sus')->default(0);
            $table->string('satuan_ukur_sus');
            $table->decimal('harga_satuan_sus', 20, 2);
            $table->integer('jumlah_ksu')->default(0);
            $table->string('satuan_ukur_ksu');
            $table->integer('total_nilai_si')->default(0);
            $table->string('satuan_ukur_si');
            $table->decimal('harga_satuan_ukur_si', 20, 2);
            $table->decimal('sub_total', 20, 2);
            $table->timestamps();

            $table->foreign('satuan_ukur_sus')->references('satuan')->on('list_unit_measure_estimates')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('satuan_ukur_ksu')->references('satuan')->on('list_unit_measure_estimates')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('satuan_ukur_si')->references('satuan')->on('list_unit_measure_estimates')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complement_based_materials');
    }
};
