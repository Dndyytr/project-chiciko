<?php

namespace App\Observers;

use App\Models\CoordinatorCode;
use App\Models\TailorCode;
use Illuminate\Support\Facades\DB;

class CoordinatorCodeObserver
{
    // Batas data besar (bisa diatur sesuai kebutuhan)
    private const LARGE_DATASET_THRESHOLD = 5000;

    public function updated(CoordinatorCode $parent): void
    {
        if (!$parent->isDirty(['nama_koordinator', 'kode'])) {
            return; // Tidak ada perubahan yang relevan
        }

        $tailorCodes = TailorCode::where('kode_koordinator', $parent->kode);
        $count = $tailorCodes->count();

        if ($count === 0) {
            return; // Tidak ada data terkait
        }

        // Gunakan strategi berbeda berdasarkan ukuran data
        if ($count > self::LARGE_DATASET_THRESHOLD) {
            $this->updateLargeDataset($tailorCodes, $parent);
        } else {
            $this->updateSmallDataset($tailorCodes->get(), $parent);
        }
    }

    /**
     * Update untuk dataset besar (> 5.000 record)
     * Menggunakan single query SQL - sangat cepat!
     */
    private function updateLargeDataset($query, CoordinatorCode $parent): void
    {
        $updates = [];

        // Siapkan field yang akan di-update
        if ($parent->isDirty('nama_koordinator')) {
            $updates['nama_koordinator'] = $parent->nama_koordinator;
        }

        if ($parent->isDirty('kode')) {
            $updates['kode_penjahit'] = DB::raw("
                    CONCAT(
                        kode_koordinator, '', 
                        kode_daerah, '', 
                        no_urut, '',
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
    private function updateSmallDataset($tailorCodes, CoordinatorCode $parent): void
    {
        foreach ($tailorCodes as $tailorCode) {
            $changed = false;

            // Update nama_koordinator jika nama berubah
            if ($parent->isDirty('nama_koordinator')) {
                $tailorCode->nama_koordinator = $parent->nama_koordinator;
                $changed = true;
            }

            // Update kode_penjahit jika kode berubah
            if ($parent->isDirty('kode')) {
                $tailorCode->kode_penjahit = implode('', [
                    $tailorCode->kode_koordinator,
                    $tailorCode->kode_daerah,
                    $tailorCode->no_urut
                ]);
                $changed = true;
            }

            // Simpan hanya jika ada perubahan
            if ($changed) {
                $tailorCode->save();
            }
        }
    }
}