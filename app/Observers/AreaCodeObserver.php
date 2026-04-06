<?php

namespace App\Observers;

use App\Models\AreaCode;
use App\Models\TailorCode;
use Illuminate\Support\Facades\DB;

class AreaCodeObserver
{
    // Batas data besar (bisa diatur sesuai kebutuhan)
    private const LARGE_DATASET_THRESHOLD = 5000;

    public function updated(AreaCode $parent): void
    {
        if (!$parent->isDirty(['nama_daerah', 'kode'])) {
            return; // Tidak ada perubahan yang relevan
        }

        $tailorCodes = TailorCode::where('kode_daerah', $parent->kode);
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
    private function updateLargeDataset($query, AreaCode $parent): void
    {
        $updates = [];

        // Siapkan field yang akan di-update
        if ($parent->isDirty('nama_daerah')) {
            $updates['nama_daerah'] = $parent->nama_daerah;
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
    private function updateSmallDataset($tailorCodes, AreaCode $parent): void
    {
        foreach ($tailorCodes as $tailorCode) {
            $changed = false;

            // Update nama_daerah jika nama berubah
            if ($parent->isDirty('nama_daerah')) {
                $tailorCode->nama_daerah = $parent->nama_daerah;
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