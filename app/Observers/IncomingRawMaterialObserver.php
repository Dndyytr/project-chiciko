<?php

namespace App\Observers;

use App\Models\IncomingRawMaterial;
use App\Models\PurchaseBasedNote;
use App\Models\PurchaseBasedRoll;
use App\Models\PurchaseBasedYard;
use Illuminate\Support\Facades\DB;
use App\Models\StockOpname;
use App\Models\StockRawMaterial;


class IncomingRawMaterialObserver
{
    public function created(IncomingRawMaterial $material)
    {
        $this->updateOrCreatePurchaseNote($material);
    }

    public function updated(IncomingRawMaterial $material)
    {
        $this->updateOrCreatePurchaseBasedRoll($material);
        $this->updateOrCreatePurchaseBasedYard($material);

        // Update stock opnames jika ada perubahan pada field kritis
        $this->updateRelatedStockOpnames($material);

        // Update stock raw materials jika ada perubahan yang mempengaruhi harga atau yard
        $this->updateRelatedStockRawMaterials($material);
    }

    public function deleted(IncomingRawMaterial $material)
    {
        $this->updateOrCreatePurchaseNote($material);
        $this->updateOrCreatePurchaseBasedRoll($material);
        $this->updateOrCreatePurchaseBasedYard($material);

        // Hapus stock opnames saat material dihapus
        StockOpname::where('material_type', IncomingRawMaterial::class)
            ->where('material_id', $material->id)
            ->delete();
    }

    protected function updateOrCreatePurchaseNote($material)
    {
        // Hitung total dari semua incoming raw materials dengan nota yang sama
        $totals = IncomingRawMaterial::where([
            ['tanggal_nota', $material->tanggal_nota],
            ['no_kwitansi', $material->no_kwitansi],
            ['kode_supplier', $material->kode_supplier],
        ])->selectRaw('
            SUM(qty_roll) as total_qty_roll,
            SUM(yard) as total_qty_yard,
            SUM(total_harga) as total_jumlah,
            nama_supplier
        ')->groupBy('nama_supplier')->first();

        if ($totals) {
            // Update atau buat record baru
            PurchaseBasedNote::updateOrCreate(
                [
                    'tanggal_nota' => $material->tanggal_nota,
                    'no_kwitansi' => $material->no_kwitansi,
                    'kode_supplier' => $material->kode_supplier,
                ],
                [
                    'nama_supplier' => $totals->nama_supplier,
                    'qty_roll' => $totals->total_qty_roll ?? 0,
                    'qty_yard' => $totals->total_qty_yard ?? 0,
                    'jumlah' => $totals->total_jumlah ?? 0,
                ]
            );
        } else {
            // Jika tidak ada data incoming raw materials, hapus purchase based note
            PurchaseBasedNote::where([
                ['tanggal_nota', $material->tanggal_nota],
                ['no_kwitansi', $material->no_kwitansi],
                ['kode_supplier', $material->kode_supplier],
            ])->delete();
        }
    }

    protected function updateOrCreatePurchaseBasedRoll(IncomingRawMaterial $material)
    {
        // Cari semua IncomingRawMaterial dengan kode_barcode yang sama
        $materials = IncomingRawMaterial::where('id', $material->id)->get();

        if ($materials->isEmpty()) {
            // Jika tidak ada data (mungkin semua dihapus), hapus PurchaseBasedRoll
            PurchaseBasedRoll::where('incoming_raw_materials_id', $material->id)->delete();
            return;
        }

        // Ambil data dari material pertama (asumsi konsisten)
        $firstMaterial = $materials->first();


        // Cek apakah record sudah ada
        if (PurchaseBasedRoll::where('incoming_raw_materials_id', $material->id)->exists()) {
            PurchaseBasedRoll::where('incoming_raw_materials_id', $material->id)->update([
                'kode_barcode' => $firstMaterial->kode_barcode,
                'nama_barang' => $firstMaterial->nama_barang,
                'jenis_kain' => $firstMaterial->jenis_kain,
                'warna' => $firstMaterial->warna,
                'qty_roll' => $firstMaterial->qty_roll,

                'jumlah_roll_satuan' => $firstMaterial->jumlah_roll_satuan,
                'total_harga' => $firstMaterial->total_harga,
                'harga_per_satuan' => $firstMaterial->jumlah_roll_satuan > 0 ? $firstMaterial->total_harga / $firstMaterial->jumlah_roll_satuan : 0,
            ]);
        }
    }

    protected function updateOrCreatePurchaseBasedYard(IncomingRawMaterial $material)
    {
        // Cari semua IncomingRawMaterial dengan kode_barcode yang sama
        $materials = IncomingRawMaterial::where('id', $material->id)->get();

        if ($materials->isEmpty()) {
            // Jika tidak ada data (mungkin semua dihapus), hapus PurchaseBasedRoll
            PurchaseBasedYard::where('incoming_raw_materials_id', $material->id)->delete();
            return;
        }

        $totalJumlahRollSatuan = $materials->sum(function ($item) {
            return $item->qty_roll * $item->yard;
        });

        // Ambil data dari material pertama (asumsi konsisten)
        $firstMaterial = $materials->first();

        // Cek apakah record sudah ada
        if (PurchaseBasedYard::where('incoming_raw_materials_id', $material->id)->exists()) {
            PurchaseBasedYard::where('incoming_raw_materials_id', $material->id)->update([
                'kode_barcode' => $firstMaterial->kode_barcode,
                'nama_barang' => $firstMaterial->nama_barang,
                'jenis_kain' => $firstMaterial->jenis_kain,
                'warna' => $firstMaterial->warna,
                'qty_roll' => $firstMaterial->qty_roll,

                'yard_per_roll' => $firstMaterial->yard,
                'jumlah_roll_satuan' => $totalJumlahRollSatuan,
                'total_harga' => $firstMaterial->total_harga,
                'harga_per_satuan' => $totalJumlahRollSatuan > 0 ? $firstMaterial->total_harga / $totalJumlahRollSatuan : 0,
            ]);
        }
    }

    protected function updateRelatedStockOpnames(IncomingRawMaterial $material)
    {
        $dirty = $material->getDirty();

        // 1. Jika qty_roll berubah, update buku dan selisih
        if (array_key_exists('qty_roll', $dirty)) {
            StockOpname::where('material_type', IncomingRawMaterial::class)
                ->where('material_id', $material->id)
                ->update([
                    'buku' => $material->qty_roll,
                    'selisih' => DB::raw("{$material->qty_roll} - fisik")
                ]);
        }

        // 2. Jika field lain berubah, update field terkait
        $fieldsToUpdate = [];
        if (array_key_exists('kode_barcode', $dirty)) {
            $fieldsToUpdate['kode_item'] = $material->kode_barcode;
            $fieldsToUpdate['kode_barcode'] = $material->kode_barcode;
        }
        if (array_key_exists('nama_barang_detail', $dirty)) {
            $fieldsToUpdate['nama_item'] = $material->nama_barang_detail;
        }
        if (array_key_exists('satuan_ukur', $dirty)) {
            $fieldsToUpdate['satuan'] = $material->satuan_ukur;
        }

        if (!empty($fieldsToUpdate)) {
            StockOpname::where('material_type', IncomingRawMaterial::class)
                ->where('material_id', $material->id)
                ->update($fieldsToUpdate);
        }
    }

    protected function updateRelatedStockRawMaterials(IncomingRawMaterial $material)
    {
        $dirty = $material->getDirty();

        // Field yang mempengaruhi perhitungan
        $affectedFields = ['harga_per_satuan', 'yard', 'qty_roll', 'jumlah_roll_satuan'];

        // Field yang mempengaruhi informasi item (tidak perlu recalculate, hanya update field)
        $itemFields = ['nama_barang_detail'];

        // Cek apakah ada perubahan pada field yang mempengaruhi perhitungan
        $hasAffectedChange = count(array_intersect(array_keys($dirty), $affectedFields)) > 0;

        // cek apakah ada perubahan pada field item
        $hasItemChange = count(array_intersect(array_keys($dirty), $itemFields)) > 0;


        if (!$hasAffectedChange && !$hasItemChange) {
            return; // Tidak ada perubahan yang mempengaruhi perhitungan
        }

        // Ambil semua stock raw material yang terkait
        $stockRawMaterials = StockRawMaterial::where('incoming_raw_materials_id', $material->id)->get();

        foreach ($stockRawMaterials as $stockRawMaterial) {
            $updateData = [];

            // Tentukan apakah menggunakan stock dari stock opname atau dari qty_roll
            $useStockOpname = false;
            $stockAkhir = 0;

            if ($stockRawMaterial->stock_opnames_id) {
                // Cek apakah stock opname masih ada dan valid
                $stockOpname = StockOpname::find($stockRawMaterial->stock_opnames_id);

                if ($stockOpname) {
                    $useStockOpname = true;
                    $stockAkhir = $stockOpname->fisik; // Gunakan fisik dari stock opname
                }
            }

            // Jika tidak ada stock opname atau tidak valid, gunakan qty_roll dari incoming
            if (!$useStockOpname) {
                $stockAkhir = $material->qty_roll;
            }

            // === KALKULASI SESUAI JAVASCRIPT ===

            // 1. Hitung keluar_roll (dari keluar_yard yang sudah ada)
            $keluarYard = (float) $stockRawMaterial->keluar_yard;
            $yard = (float) $material->yard;

            if ($yard == 0) {
                continue; // Skip jika yard = 0 untuk menghindari division by zero
            }

            $keluarRoll = $keluarYard - $yard;

            // 2. Hitung sisa_yard
            $jumlahRollSatuan = (float) $material->jumlah_roll_satuan;
            $sisaYard = $jumlahRollSatuan - $keluarYard;

            // 3. Hitung sisa_roll
            $sisaRoll = round($sisaYard / $yard, 5);

            // 4. Hitung total_harga
            $hargaPerSatuan = (float) $material->harga_per_satuan;
            $totalHarga = round($sisaYard * $hargaPerSatuan, 2);

            // 5. Hitung harga_per_satuan (untuk stock raw material)
            // Sesuai JS: hargaPerSatuan = (sisaYard === 0) ? 0 : totalHarga / sisaYard
            $calculatedHargaPerSatuan = $sisaYard > 0 ? round($totalHarga / $sisaYard, 2) : 0;

            // Siapkan data untuk update
            $updateData = [
                'keluar_roll' => $keluarRoll,
                'sisa_yard' => $sisaYard,
                'sisa_roll' => $sisaRoll,
                'total_harga' => $totalHarga,
                'harga_per_satuan' => $calculatedHargaPerSatuan,
            ];

            // Update stock_akhir hanya jika TIDAK menggunakan stock opname
            if (!$useStockOpname) {
                $updateData['stock_akhir'] = $stockAkhir;
            }

            // === UPDATE FIELD ITEM ===
            if (array_key_exists('nama_barang_detail', $dirty)) {
                $updateData['nama_item'] = $material->nama_barang_detail;
            }

            // Update record jika ada data yang perlu diupdate
            if (!empty($updateData)) {
                $stockRawMaterial->update($updateData);
            }
        }
    }
}
