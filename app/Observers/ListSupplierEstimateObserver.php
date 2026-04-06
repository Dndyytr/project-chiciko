<?php

namespace App\Observers;

use App\Models\IncomingComplementMaterial;
use App\Models\ListSupplierEstimate;
use App\Models\IncomingRawMaterial;
use App\Models\PurchaseBasedNote;

class ListSupplierEstimateObserver
{
    // Batas data besar (bisa diatur sesuai kebutuhan)
    private const LARGE_DATASET_THRESHOLD = 5000;

    /**
     * Handle the ListSupplierEstimate "updated" event.
     */
    public function updated(ListSupplierEstimate $parent): void
    {
        // Cek apakah kolom 'nama_supplier' yang diubah
        if (!$parent->wasChanged('nama_supplier')) {
            return;
        }

        // Update untuk tiga model: IncomingRawMaterial, PurchaseBasedNote, IncomingComplementMaterial
        $this->updateRelatedRecords(IncomingRawMaterial::class, 'kode_supplier', $parent->kode, [
            'nama_supplier' => $parent->nama_supplier,
            'updated_at' => now()
        ]);

        $this->updateRelatedRecords(PurchaseBasedNote::class, 'kode_supplier', $parent->kode, [
            'nama_supplier' => $parent->nama_supplier,
            'updated_at' => now()
        ]);

        $this->updateRelatedRecords(IncomingComplementMaterial::class, 'kode_supplier', $parent->kode, [
            'nama_supplier' => $parent->nama_supplier,
            'updated_at' => now()
        ]);
    }

    /**
     * Update records terkait dengan strategi berbeda berdasarkan ukuran dataset
     */
    private function updateRelatedRecords(string $modelClass, string $foreignKey, string $kode, array $updates): void
    {
        $query = $modelClass::where($foreignKey, $kode);
        $count = $query->count();

        if ($count === 0) {
            return;
        }

        if ($count > self::LARGE_DATASET_THRESHOLD) {
            $this->updateLargeDataset($query, $updates);
        } else {
            $this->updateSmallDataset($query->get(), $updates);
        }
    }

    /**
     * Update untuk dataset besar (> 5.000 record)
     * Menggunakan single query SQL - sangat cepat!
     */
    private function updateLargeDataset($query, array $updates): void
    {
        if (!empty($updates)) {
            $query->update($updates);
        }
    }

    /**
     * Update untuk dataset kecil (< 5.000 record)
     * Tetap sederhana dan mudah dimengerti
     */
    private function updateSmallDataset($records, array $updates): void
    {
        foreach ($records as $record) {
            foreach ($updates as $field => $value) {
                $record->{$field} = $value;
            }
            $record->save();
        }
    }

    /**
     * Handle the ListSupplierEstimate "deleting" event.
     */
    public function deleting(ListSupplierEstimate $parent): void
    {
        // Cascade delete akan menangani penghapusan otomatis
        // Tidak perlu logika tambahan
    }
}