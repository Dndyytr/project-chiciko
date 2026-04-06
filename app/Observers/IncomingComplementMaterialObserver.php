<?php

namespace App\Observers;

use App\Models\IncomingComplementMaterial;
use App\Models\ComplementBasedNote;
use Illuminate\Support\Facades\DB;
use App\Models\ComplementBasedMaterial;
use App\Models\StockOpname;
use App\Models\StockComplementMaterial;

class IncomingComplementMaterialObserver
{
    // Batas data besar
    private const LARGE_DATASET_THRESHOLD = 5000;

    // Field yang perlu dimonitor perubahannya
    private const TRACKED_FIELDS = [
        'nama_barang_sesuai_nota',
        'jumlah_sus',
        'satuan_ukur_sus',
        'harga_satuan_sus',
        'jumlah_ksu',
        'satuan_ukur_ksu',
        'total_nilai_si',
        'satuan_ukur_si',
        'harga_satuan_ukur_si',
        'sub_total',
    ];

    public function created(IncomingComplementMaterial $material)
    {
        $this->updateOrCreateComplementNote($material);
        $this->createComplementBasedMaterial($material);
    }

    public function updated(IncomingComplementMaterial $material)
    {
        $this->updateOrCreateComplementNote($material);
        // Cek apakah ada perubahan pada field yang dipantau
        if ($material->isDirty(self::TRACKED_FIELDS)) {
            $this->updateComplementBasedMaterials($material);
        }

        $this->updateRelatedStockOpnames($material);

        // Update stock complement materials jika ada perubahan yang mempengaruhi harga
        $this->updateRelatedStockComplementMaterials($material);

    }

    public function deleted(IncomingComplementMaterial $material)
    {
        $this->updateOrCreateComplementNote($material);

        // Update stock opnames saat material dihapus
        StockOpname::where('material_type', IncomingComplementMaterial::class)
            ->where('material_id', $material->id)
            ->delete();
    }

    protected function updateOrCreateComplementNote($material)
    {
        // Hitung total dari semua incoming complement materials dengan nota yang sama
        $totals = IncomingComplementMaterial::where([
            ['tanggal_nota', $material->tanggal_nota],
            ['no_kwitansi', $material->no_kwitansi],
            ['kode_supplier', $material->kode_supplier],
        ])->selectRaw('
            SUM(sub_total) as total_harga,
            nama_supplier
        ')->groupBy('nama_supplier')->first();

        if ($totals) {
            // Update atau buat record baru
            ComplementBasedNote::updateOrCreate(
                [
                    'tanggal_nota' => $material->tanggal_nota,
                    'no_kwitansi' => $material->no_kwitansi,
                    'kode_supplier' => $material->kode_supplier,
                ],
                [
                    'nama_supplier' => $totals->nama_supplier,
                    'total_harga' => $totals->total_harga ?? 0,
                ]
            );
        } else {
            // Jika tidak ada data incoming complement materials, hapus complement based note
            ComplementBasedNote::where([
                ['tanggal_nota', $material->tanggal_nota],
                ['no_kwitansi', $material->no_kwitansi],
                ['kode_supplier', $material->kode_supplier],
            ])->delete();
        }
    }

    protected function updateComplementBasedMaterials($material)
    {
        // Cari complement based materials yang terkait
        // Menggunakan foreign key yang benar: complement_materials_id
        $query = ComplementBasedMaterial::where('complement_materials_id', $material->id);

        $count = $query->count();
        if ($count == 0) {
            return;
        }

        // Pilih strategi berdasarkan jumlah data
        if ($count > self::LARGE_DATASET_THRESHOLD) {
            $this->updateLargeDataset($query, $material);
        } else {
            $this->updateSmallDataset($query, $material);
        }
    }

    /**
     * Update untuk dataset besar (> 5.000 record)
     * Menggunakan raw SQL untuk performa maksimal
     */
    private function updateLargeDataset($query, $material)
    {
        $updates = [];
        $bindings = [];

        // Siapkan data yang akan diupdate
        if ($material->isDirty('nama_barang_sesuai_nota')) {
            $updates[] = 'nama_barang_sesuai_nota = ?';
            $bindings[] = $material->nama_barang_sesuai_nota;
        }

        if ($material->isDirty('jumlah_sus')) {
            $updates[] = 'jumlah_sus = ?';
            $bindings[] = $material->jumlah_sus;
        }

        if ($material->isDirty('satuan_ukur_sus')) {
            $updates[] = 'satuan_ukur_sus = ?';
            $bindings[] = $material->satuan_ukur_sus;
        }

        if ($material->isDirty('harga_satuan_sus')) {
            $updates[] = 'harga_satuan_sus = ?';
            $bindings[] = $material->harga_satuan_sus;
        }

        if ($material->isDirty('jumlah_ksu')) {
            $updates[] = 'jumlah_ksu = ?';
            $bindings[] = $material->jumlah_ksu;
        }

        if ($material->isDirty('satuan_ukur_ksu')) {
            $updates[] = 'satuan_ukur_ksu = ?';
            $bindings[] = $material->satuan_ukur_ksu;
        }

        if ($material->isDirty('total_nilai_si')) {
            $updates[] = 'total_nilai_si = ?';
            $bindings[] = $material->total_nilai_si;
        }

        if ($material->isDirty('satuan_ukur_si')) {
            $updates[] = 'satuan_ukur_si = ?';
            $bindings[] = $material->satuan_ukur_si;
        }

        if ($material->isDirty('harga_satuan_ukur_si')) {
            $updates[] = 'harga_satuan_ukur_si = ?';
            $bindings[] = $material->harga_satuan_ukur_si;
        }

        if ($material->isDirty('sub_total')) {
            $updates[] = 'sub_total = ?';
            $bindings[] = $material->sub_total;
        }

        // Update menggunakan raw SQL untuk dataset besar
        if (!empty($updates)) {
            $bindings[] = $material->id;

            DB::update(
                'UPDATE complement_based_materials SET ' . implode(', ', $updates) .
                ' WHERE complement_materials_id = ?',
                $bindings
            );
        }
    }

    /**
     * Update untuk dataset kecil (< 5.000 record)
     * Menggunakan Eloquent query builder yang lebih mudah dibaca
     */
    private function updateSmallDataset($query, $material)
    {
        $updates = [];

        // Siapkan data yang akan diupdate
        if ($material->isDirty('nama_barang_sesuai_nota')) {
            $updates['nama_barang_sesuai_nota'] = $material->nama_barang_sesuai_nota;
        }

        if ($material->isDirty('jumlah_sus')) {
            $updates['jumlah_sus'] = $material->jumlah_sus;
        }

        if ($material->isDirty('satuan_ukur_sus')) {
            $updates['satuan_ukur_sus'] = $material->satuan_ukur_sus;
        }

        if ($material->isDirty('harga_satuan_sus')) {
            $updates['harga_satuan_sus'] = $material->harga_satuan_sus;
        }

        if ($material->isDirty('jumlah_ksu')) {
            $updates['jumlah_ksu'] = $material->jumlah_ksu;
        }

        if ($material->isDirty('satuan_ukur_ksu')) {
            $updates['satuan_ukur_ksu'] = $material->satuan_ukur_ksu;
        }

        if ($material->isDirty('total_nilai_si')) {
            $updates['total_nilai_si'] = $material->total_nilai_si;
        }

        if ($material->isDirty('satuan_ukur_si')) {
            $updates['satuan_ukur_si'] = $material->satuan_ukur_si;
        }

        if ($material->isDirty('harga_satuan_ukur_si')) {
            $updates['harga_satuan_ukur_si'] = $material->harga_satuan_ukur_si;
        }

        if ($material->isDirty('sub_total')) {
            $updates['sub_total'] = $material->sub_total;
        }

        // Update menggunakan Eloquent untuk dataset kecil
        if (!empty($updates)) {
            $query->update($updates);
        }
    }

    /**
     * Buat complement based material baru saat incoming material dibuat
     */
    protected function createComplementBasedMaterial($material)
    {
        ComplementBasedMaterial::create([
            'complement_materials_id' => $material->id,
            'nama_barang_sesuai_nota' => $material->nama_barang_sesuai_nota,
            'jumlah_sus' => $material->jumlah_sus,
            'satuan_ukur_sus' => $material->satuan_ukur_sus,
            'harga_satuan_sus' => $material->harga_satuan_sus,
            'jumlah_ksu' => $material->jumlah_ksu,
            'satuan_ukur_ksu' => $material->satuan_ukur_ksu,
            'total_nilai_si' => $material->total_nilai_si,
            'satuan_ukur_si' => $material->satuan_ukur_si,
            'harga_satuan_ukur_si' => $material->harga_satuan_ukur_si,
            'sub_total' => $material->sub_total,
        ]);
    }

    protected function updateRelatedStockOpnames(IncomingComplementMaterial $material)
    {
        $dirty = $material->getDirty();

        // 1. Jika jumlah_sus berubah, update buku dan selisih
        if (array_key_exists('jumlah_sus', $dirty)) {
            StockOpname::where('material_type', IncomingComplementMaterial::class)
                ->where('material_id', $material->id)
                ->update([
                    'buku' => $material->jumlah_sus,
                    'selisih' => DB::raw("{$material->jumlah_sus} - fisik")
                ]);
        }

        // 2. Jika field lain berubah, update field terkait
        $fieldsToUpdate = [];
        if (array_key_exists('kode', $dirty)) {
            $fieldsToUpdate['kode_item'] = $material->kode;
            $fieldsToUpdate['kode_barcode'] = $material->kode;
        }
        if (array_key_exists('nama_barang_sesuai_nota', $dirty)) {
            $fieldsToUpdate['nama_item'] = $material->nama_barang_sesuai_nota;
        }
        if (array_key_exists('satuan_ukur_sus', $dirty)) {
            $fieldsToUpdate['satuan'] = $material->satuan_ukur_sus;
        }

        if (!empty($fieldsToUpdate)) {
            StockOpname::where('material_type', IncomingComplementMaterial::class)
                ->where('material_id', $material->id)
                ->update($fieldsToUpdate);
        }
    }

    protected function updateRelatedStockComplementMaterials(IncomingComplementMaterial $material)
    {
        $dirty = $material->getDirty();

        // Field yang mempengaruhi perhitungan
        $calculationFields = ['harga_satuan_ukur_si', 'total_nilai_si'];

        // Field yang mempengaruhi informasi item (tidak perlu recalculate, hanya update field)
        $informationFields = ['nama_barang_sesuai_nota'];

        // Cek apakah ada perubahan pada field yang mempengaruhi perhitungan
        $hasCalculationChange = count(array_intersect(array_keys($dirty), $calculationFields)) > 0;

        // Cek apakah ada perubahan pada field informasi
        $hasInformationChange = count(array_intersect(array_keys($dirty), $informationFields)) > 0;

        // Jika tidak ada perubahan sama sekali, return
        if (!$hasCalculationChange && !$hasInformationChange) {
            return;
        }

        // Ambil semua stock complement material yang terkait
        $stockComplementMaterials = StockComplementMaterial::where('incoming_complement_materials_id', $material->id)->get();

        foreach ($stockComplementMaterials as $stockComplementMaterial) {
            $updateData = [];
            // === KALKULASI ULANG (jika ada perubahan calculation fields) ===

            // Tentukan apakah menggunakan stock dari stock opname atau dari total_nilai_si
            $useStockOpname = false;
            $stockAkhir = 0;

            if ($stockComplementMaterial->stock_opnames_id) {
                // Cek apakah stock opname masih ada dan valid
                $stockOpname = StockOpname::find($stockComplementMaterial->stock_opnames_id);

                if ($stockOpname) {
                    $useStockOpname = true;
                    $stockAkhir = $stockOpname->fisik; // Gunakan fisik dari stock opname
                }
            }

            // Jika tidak ada stock opname atau tidak valid, gunakan total_nilai_si dari incoming
            if (!$useStockOpname) {
                $stockAkhir = $material->total_nilai_si;
            }

            // === KALKULASI UNTUK COMPLEMENT MATERIAL ===

            $hargaSatuanUkurSi = (float) $material->harga_satuan_ukur_si;
            $barangKeluar = (int) $stockComplementMaterial->barang_keluar;

            // Hitung harga_barang_keluar
            $hargaBarangKeluar = round($hargaSatuanUkurSi * $barangKeluar, 2);

            // Hitung total_harga_stock_akhir
            $totalHargaStockAkhir = round($hargaSatuanUkurSi * $stockAkhir, 2);

            // Hitung harga_satuan_stock_akhir
            $hargaSatuanStockAkhir = $stockAkhir > 0 ? round($totalHargaStockAkhir / $stockAkhir, 2) : 0;

            // Tambahkan ke update data
            $updateData = [
                'harga_barang_keluar' => $hargaBarangKeluar,
                'total_harga_stock_akhir' => $totalHargaStockAkhir,
                'harga_satuan_stock_akhir' => $hargaSatuanStockAkhir,
            ];

            // Update stock_akhir hanya jika TIDAK menggunakan stock opname
            if (!$useStockOpname) {
                $updateData['stock_akhir'] = $stockAkhir;
            }

            // === UPDATE INFORMASI FIELD ===
            if (array_key_exists('nama_barang_sesuai_nota', $dirty)) {
                $updateData['nama_item'] = $material->nama_barang_sesuai_nota;
            }

            // Update record jika ada data yang perlu diupdate
            if (!empty($updateData)) {
                $stockComplementMaterial->update($updateData);
            }
        }
    }
}