<?php

namespace App\Observers;

use App\Models\DataWarehouse;
use App\Models\StockOpname;

class DataWarehouseObserver
{
    // Batas data besar (bisa diatur sesuai kebutuhan)
    private const LARGE_DATASET_THRESHOLD = 5000;

    public function updated(DataWarehouse $parent): void
    {
        // Cek apakah kolom 'nama_gudang' yang diubah
        if (!$parent->wasChanged('nama_gudang')) {
            return;
        }

        $this->updateRelatedRecords(StockOpname::class, 'data_warehouses_id', $parent->id, [
            'nama_gudang' => $parent->nama_gudang,
            'updated_at' => now()
        ]);

    }

    /**
     * Update records terkait dengan strategi berbeda berdasarkan ukuran dataset
     */
    private function updateRelatedRecords(string $modelClass, string $foreignKey, string $id, array $updates): void
    {
        $query = $modelClass::where($foreignKey, $id);
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
     * Handle the DataWarehouse "deleting" event.
     */
    public function deleting(DataWarehouse $parent): void
    {
        // Cascade delete akan menangani penghapusan otomatis
        // Tidak perlu logika tambahan
    }

}