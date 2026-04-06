<?php

namespace App\Observers;

use App\Models\DatabaseMaterial;
use App\Models\IncomingComplementMaterial;

class DatabaseMaterialObserver
{
    /**
     * Handle the DatabaseMaterial "updated" event.
     */

    // Batas data besar (bisa diatur sesuai kebutuhan)
    private const LARGE_DATASET_THRESHOLD = 5000;

    public function updated(DatabaseMaterial $parent): void
    {
        if (!$parent->isDirty(['name', 'kode_bahan'])) {
            return; // Tidak ada perubahan yang relevan
        }

        $incomingComplementMaterials = IncomingComplementMaterial::where('database_materials_id', $parent->id);
        $count = $incomingComplementMaterials->count();

        if ($count === 0) {
            return; // Tidak ada data terkait
        }

        // Gunakan strategi berbeda berdasarkan ukuran data
        if ($count > self::LARGE_DATASET_THRESHOLD) {
            $this->updateLargeDataset($incomingComplementMaterials, $parent);
        } else {
            $this->updateSmallDataset($incomingComplementMaterials->get(), $parent);
        }

    }

    /**
     * Update untuk dataset besar (> 5.000 record)
     * Menggunakan single query SQL - sangat cepat!
     */
    private function updateLargeDataset($query, DatabaseMaterial $parent): void
    {
        $updates = [];

        // Siapkan field yang akan di-update
        if ($parent->isDirty(['name', 'kode_bahan'])) {
            $updates['jenis'] = $parent->name;
            $updates['kode'] = $parent->kode_bahan;
        }

        if (!empty($updates)) {
            $query->update($updates);
        }
    }

    /**
     * Update untuk dataset kecil (< 5.000 record)
     * Tetap sederhana dan mudah dimengerti
     */
    private function updateSmallDataset($incomingComplementMaterials, DatabaseMaterial $parent): void
    {
        foreach ($incomingComplementMaterials as $incomingComplementMaterial) {
            $changed = false;

            // Update jenis dan kode jika name berubah
            if ($parent->isDirty(['name', 'kode_bahan'])) {
                $incomingComplementMaterial->jenis = $parent->name;
                $incomingComplementMaterial->kode = $parent->kode_bahan;
                $changed = true;
            }

            // Simpan hanya jika ada perubahan
            if ($changed) {
                $incomingComplementMaterial->save();
            }
        }
    }

    /**
     * Handle the DatabaseMaterial "deleting" event.
     * Optional: Jika ingin menangani ketika DatabaseMaterial dihapus
     */
    public function deleting(DatabaseMaterial $parent): void
    {
        // Inventory stocks akan otomatis terhapus karena cascade delete
        // di foreign key constraint, tapi bisa ditambahkan logic tambahan di sini
    }
}