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
        Schema::create('summary_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->decimal('total_keseluruhan', 20, 2);
            $table->timestamps();

            $table->index(['tanggal_mulai', 'tanggal_akhir'], 'summary_expenses_idx');
        });

        Schema::create('summary_expense_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('summary_expenses_id');
            $table->foreignId('data_expenses_id');
            $table->string('kategori');
            $table->decimal('total_uang_keluar', 20, 2);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->foreign('summary_expenses_id', 'sed_se_fk')
                ->references('id')
                ->on('summary_expenses')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('data_expenses_id', 'sed_de_fk')
                ->references('id')
                ->on('data_expenses')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('kategori')->references('nama_kategori')->on('category_expenses')->onDelete('cascade')->onUpdate('cascade');

            $table->index(['kategori', 'total_uang_keluar', 'urutan'], 'sed_idx');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_expense_details');
        Schema::dropIfExists('summary_expenses');
    }
};
