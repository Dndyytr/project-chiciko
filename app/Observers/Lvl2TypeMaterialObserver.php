<?php

namespace App\Observers;

use App\Models\Lvl2TypeMaterial;
use App\Models\DatabaseMaterial;
use Illuminate\Support\Facades\DB;

class Lvl2TypeMaterialObserver
{
    // Batas data besar (bisa diatur sesuai kebutuhan)
    private const LARGE_DATASET_THRESHOLD = 5000;

    public function updated(Lvl2TypeMaterial $parent): void
    {
        if (!$parent->isDirty(['nama', 'kode'])) {
            return; // Tidak ada perubahan yang relevan
        }

        $subMaterials = DatabaseMaterial::where('kode_lvl2_jb', $parent->kode);
        $count = $subMaterials->count();

        if ($count === 0) {
            return; // Tidak ada data terkait
        }

        // Gunakan strategi berbeda berdasarkan ukuran data
        if ($count > self::LARGE_DATASET_THRESHOLD) {
            $this->updateLargeDataset($subMaterials, $parent);
        } else {
            $this->updateSmallDataset($subMaterials->get(), $parent);
        }
    }

    /**
     * Update untuk dataset besar (> 5.000 record)
     * Menggunakan single query SQL - sangat cepat!
     */
    private function updateLargeDataset($query, Lvl2TypeMaterial $parent): void
    {
        $updates = [];

        // Siapkan field yang akan di-update
        if ($parent->isDirty('nama')) {
            $updates['text_lvl2_jb'] = $parent->nama;
        }

        if ($parent->isDirty('kode')) {
            $updates['kode_bahan'] = DB::raw("
                    CONCAT(
                        kode_jp, '.', 
                        kode_lvl1_jb, '.', 
                        kode_lvl2_jb, '.', 
                        kode_lvl3_jb, '.', 
                        kode_warna
                    )
                ");
        }

        if (!empty($updates)) {
            $query->update($updates);

        }
    }

    /**
     * Update untuk dataset kecil (< 5.000 record)
     * Tetap sederhana dan mudah dimengerti
     */
    private function updateSmallDataset($subMaterials, Lvl2TypeMaterial $parent): void
    {
        foreach ($subMaterials as $sub) {
            $changed = false;

            // Update text_lvl2_jb jika nama berubah
            if ($parent->isDirty('nama')) {
                $sub->text_lvl2_jb = $parent->nama;
                $changed = true;
            }

            // Update kode_bahan jika kode berubah
            if ($parent->isDirty('kode')) {
                $sub->kode_bahan = implode('.', [
                    $sub->kode_jp,
                    $sub->kode_lvl1_jb,
                    $sub->kode_lvl2_jb,
                    $sub->kode_lvl3_jb,
                    $sub->kode_warna
                ]);
                $changed = true;
            }

            // Simpan hanya jika ada perubahan
            if ($changed) {
                $sub->save();
            }
        }
    }
}