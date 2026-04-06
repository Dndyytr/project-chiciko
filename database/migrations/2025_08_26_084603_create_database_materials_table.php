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
        Schema::create('database_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->string('kode_bahan');
            $table->string('text_jp');
            $table->string('kode_jp');
            $table->string('text_lvl1_jb');
            $table->string('kode_lvl1_jb');
            $table->string('text_lvl2_jb');
            $table->string('kode_lvl2_jb');
            $table->string('text_lvl3_jb');
            $table->string('kode_lvl3_jb');
            $table->string('text_warna');
            $table->string('kode_warna');
            $table->timestamps();

            $table->foreign('kode_jp')->references('kode')->on('list_accounting_estimates')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_lvl1_jb')->references('kode')->on('lvl1_type_materials')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_lvl2_jb')->references('kode')->on('lvl2_type_materials')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_lvl3_jb')->references('kode')->on('lvl3_type_materials')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_warna')->references('kode')->on('list_color_estimates')->onDelete('cascade')->onUpdate('cascade');

            $table->index(['kode_lvl1_jb', 'kode_lvl2_jb', 'kode_lvl3_jb'], 'sub_raw_material_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_materials');
    }
};
