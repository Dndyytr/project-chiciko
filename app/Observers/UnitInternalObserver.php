<?php

namespace App\Observers;

use App\Models\UnitInternal;
use App\Models\IncomingComplementMaterial;
use Illuminate\Support\Facades\DB;

class UnitInternalObserver
{
    private const LARGE_DATASET_THRESHOLD = 5000;

    /**
     * Handle the UnitInternal "updated" event.
     */
    public function updated(UnitInternal $parent): void
    {
        // Hanya lanjutkan jika kolom 'nilai' berubah
        if (!$parent->wasChanged('nilai')) {
            return;
        }

        $query = IncomingComplementMaterial::where('unit_internals_id', $parent->id);
        $count = $query->count();

        if ($count === 0) {
            return;
        }

        if ($count > self::LARGE_DATASET_THRESHOLD) {
            $this->updateLargeDataset($query, $parent);
        } else {
            $this->updateSmallDataset($query->get(), $parent);
        }
    }

    /**
     * Update untuk dataset besar (> 5.000 record)
     * Menggunakan single query SQL - sangat cepat!
     */
    private function updateLargeDataset($query, UnitInternal $parent): void
    {
        $newKsuValue = $parent->nilai;

        $query->update([
            'jumlah_ksu' => $newKsuValue, // ✅ UPDATE JUMLAH_KSU
            'total_nilai_si' => DB::raw("jumlah_sus * {$newKsuValue}"),
            'harga_satuan_ukur_si' => DB::raw("IF(jumlah_sus * {$newKsuValue} > 0, (harga_satuan_sus * jumlah_sus) / (jumlah_sus * {$newKsuValue}), 0)"),
            'sub_total' => DB::raw("harga_satuan_sus * jumlah_sus") // ✅ sub_total = harga_satuan_sus * jumlah_sus
        ]);
    }

    /**
     * Update untuk dataset kecil (< 5.000 record)
     */
    private function updateSmallDataset($materials, UnitInternal $parent): void
    {
        $newKsuValue = $parent->nilai;

        foreach ($materials as $material) {
            // ✅ UPDATE SEMUA FIELD YANG TERPENGARUH
            $material->update([
                'jumlah_ksu' => $newKsuValue,
                'total_nilai_si' => round($material->jumlah_sus * $newKsuValue, 2),
                'harga_satuan_ukur_si' => round(
                    ($material->jumlah_sus * $newKsuValue > 0)
                    ? ($material->harga_satuan_sus * $material->jumlah_sus) / ($material->jumlah_sus * $newKsuValue)
                    : 0,
                    2
                ),
                'sub_total' => round($material->harga_satuan_sus * $material->jumlah_sus, 2)
            ]);
        }
    }

    /**
     * Handle the UnitInternal "deleting" event.
     */
    public function deleting(UnitInternal $parent): void
    {
        // Cascade delete akan menangani penghapusan otomatis
    }
}