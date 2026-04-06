<?php

namespace App\Observers;

use App\Models\TailorCode;
use App\Models\DataTailor;
use Illuminate\Support\Facades\DB;

class TailorCodeObserver
{
    // Batas data besar
    private const LARGE_DATASET_THRESHOLD = 5000;

    // Field yang perlu dimonitor perubahannya
    private const TRACKED_FIELDS = [
        'nama_koordinator',
        'kode_koordinator',
        'nama_daerah',
        'nama_penjahit',
        'kode_penjahit',
    ];

    public function created(TailorCode $tailorCode)
    {
        $this->createDataTailor($tailorCode);
    }

    public function updated(TailorCode $tailorCode)
    {
        // Cek apakah ada perubahan pada field yang dipantau
        if ($tailorCode->isDirty(self::TRACKED_FIELDS)) {
            $this->updateDataTailors($tailorCode);
        }
    }

    public function deleted(TailorCode $tailorCode)
    {
        // Hapus semua data tailor yang terkait
        DataTailor::where('kode_penjahit', $tailorCode->kode_penjahit)->delete();
    }

    /**
     * Buat data tailor baru saat tailor code dibuat
     */
    protected function createDataTailor($tailorCode)
    {
        DataTailor::create([
            'nama_koordinator' => $tailorCode->nama_koordinator,
            'kode_koordinator' => $tailorCode->kode_koordinator,
            'nama_penjahit' => $tailorCode->nama_penjahit,
            'nama_daerah' => $tailorCode->nama_daerah,
            'kode_penjahit' => $tailorCode->kode_penjahit,
        ]);
    }

    /**
     * Update data tailors yang terkait dengan tailor code
     */
    protected function updateDataTailors($tailorCode)
    {
        // Cari data tailors yang terkait
        $query = DataTailor::where('kode_penjahit', $tailorCode->kode_penjahit);

        $count = $query->count();
        if ($count == 0) {
            return;
        }

        // Pilih strategi berdasarkan jumlah data
        if ($count > self::LARGE_DATASET_THRESHOLD) {
            $this->updateLargeDataset($query, $tailorCode);
        } else {
            $this->updateSmallDataset($query, $tailorCode);
        }
    }

    /**
     * Update untuk dataset besar (> 5.000 record)
     * Menggunakan raw SQL untuk performa maksimal
     */
    private function updateLargeDataset($query, $tailorCode)
    {
        $updates = [];
        $bindings = [];

        // Siapkan data yang akan diupdate
        if ($tailorCode->isDirty('nama_koordinator')) {
            $updates[] = 'nama_koordinator = ?';
            $bindings[] = $tailorCode->nama_koordinator;
        }

        if ($tailorCode->isDirty('kode_koordinator')) {
            $updates[] = 'kode_koordinator = ?';
            $bindings[] = $tailorCode->kode_koordinator;
        }

        if ($tailorCode->isDirty('nama_penjahit')) {
            $updates[] = 'nama_penjahit = ?';
            $bindings[] = $tailorCode->nama_penjahit;
        }

        if ($tailorCode->isDirty('nama_daerah')) {
            $updates[] = 'nama_daerah = ?';
            $bindings[] = $tailorCode->nama_daerah;
        }

        if ($tailorCode->isDirty('kode_penjahit')) {
            $updates[] = 'kode_penjahit = ?';
            $bindings[] = $tailorCode->kode_penjahit;
        }

        // Update menggunakan raw SQL untuk dataset besar
        if (!empty($updates)) {
            $bindings[] = $tailorCode->kode_penjahit;

            DB::update(
                'UPDATE data_tailors SET ' . implode(', ', $updates) .
                ' WHERE kode_penjahit = ?',
                $bindings
            );
        }
    }

    /**
     * Update untuk dataset kecil (< 5.000 record)
     * Menggunakan Eloquent query builder yang lebih mudah dibaca
     */
    private function updateSmallDataset($query, $tailorCode)
    {
        $updates = [];

        // Siapkan data yang akan diupdate
        if ($tailorCode->isDirty('nama_koordinator')) {
            $updates['nama_koordinator'] = $tailorCode->nama_koordinator;
        }

        if ($tailorCode->isDirty('kode_koordinator')) {
            $updates['kode_koordinator'] = $tailorCode->kode_koordinator;
        }

        if ($tailorCode->isDirty('nama_penjahit')) {
            $updates['nama_penjahit'] = $tailorCode->nama_penjahit;
        }

        if ($tailorCode->isDirty('nama_daerah')) {
            $updates['nama_daerah'] = $tailorCode->nama_daerah;
        }

        if ($tailorCode->isDirty('kode_penjahit')) {
            $updates['kode_penjahit'] = $tailorCode->kode_penjahit;
        }

        // Update menggunakan Eloquent untuk dataset kecil
        if (!empty($updates)) {
            $query->update($updates);
        }
    }
}