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
        Schema::create('stock_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_raw_materials_id')->constrained('incoming_raw_materials')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('stock_opnames_id')->nullable()->constrained('stock_opnames')->onDelete('set null')->onUpdate('cascade');
            $table->text('nama_item');
            $table->float('keluar_roll')->default(0);
            $table->integer('keluar_yard')->default(0);
            $table->integer('stock_akhir')->default(0);
            $table->float('sisa_roll')->default(0);
            $table->decimal('sisa_yard', 20, 2);
            $table->decimal('total_harga', 20, 2);
            $table->decimal('harga_per_satuan', 20, 2);
            $table->timestamps();

            $table->index(['incoming_raw_materials_id', 'stock_opnames_id', 'keluar_roll', 'keluar_yard', 'stock_akhir'], 'stock_rm_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_raw_materials');
    }
};
