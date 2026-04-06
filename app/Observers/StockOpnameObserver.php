<?php

namespace App\Observers;

use App\Models\StockOpname;
use App\Models\StockRawMaterial;
use App\Models\StockComplementMaterial;
use App\Models\IncomingRawMaterial;
use App\Models\IncomingComplementMaterial;

class StockOpnameObserver
{
    /**
     * Saat stock opname diupdate
     */
    public function updated(StockOpname $stockOpname)
    {
        $this->updateRelatedStockMaterials($stockOpname);
    }

    /**
     * Saat stock opname akan dihapus (sebelum dihapus dari database)
     */
    public function deleting(StockOpname $stockOpname)
    {
        // Update stock materials dengan mengecualikan stock opname yang sedang dihapus
        $this->updateStockMaterialsAfterDelete($stockOpname);
    }

    /**
     * Update semua stock material terkait berdasarkan material_type
     */
    protected function updateRelatedStockMaterials(StockOpname $stockOpname)
    {
        $stockMaterials = $this->getStockMaterialsByType($stockOpname->material_type, $stockOpname->id);

        foreach ($stockMaterials as $stockMaterial) {
            // Tidak perlu exclude karena ini untuk update/create
            $this->updateSingleStockMaterial($stockMaterial, $stockOpname, null);
        }
    }

    /**
     * Update setelah stock opname dihapus
     */
    protected function updateStockMaterialsAfterDelete(StockOpname $stockOpname)
    {
        $stockMaterials = $this->getStockMaterialsByType($stockOpname->material_type, $stockOpname->id);

        foreach ($stockMaterials as $stockMaterial) {
            // Pass ID stock opname yang sedang dihapus untuk dikecualikan dari query
            $this->updateSingleStockMaterial($stockMaterial, $stockOpname, $stockOpname->id);
        }
    }

    /**
     * Dapatkan stock materials berdasarkan material_type
     * 
     * 
     */
    protected function getStockMaterialsByType($materialType, $stockOpnameId)
    {
        if ($materialType === IncomingRawMaterial::class || $materialType === 'App\\Models\\IncomingRawMaterial') {
            return StockRawMaterial::where('stock_opnames_id', $stockOpnameId)->get();
        } elseif ($materialType === IncomingComplementMaterial::class || $materialType === 'App\\Models\\IncomingComplementMaterial') {
            return StockComplementMaterial::where('stock_opnames_id', $stockOpnameId)->get();
        }

        return collect(); // Return empty collection jika material_type tidak dikenali
    }

    /**
     * Update satu stock material
     * 
     * 
     */
    protected function updateSingleStockMaterial($stockMaterial, StockOpname $stockOpname, $excludeStockOpnameId = null)
    {
        // Dapatkan incoming material berdasarkan material_type
        $incomingMaterial = $this->getIncomingMaterial($stockMaterial, $stockOpname->material_type);

        if (!$incomingMaterial) {
            return;
        }

        // Dapatkan stock opname terbaru untuk material ini
        $latestStockOpname = $this->getLatestStockOpname(
            $stockOpname->material_id,
            $stockOpname->material_type,
            $excludeStockOpnameId
        );

        // Tentukan nilai stock_akhir dan stock_opnames_id
        // Untuk Raw Material: gunakan qty_roll jika tidak ada stock opname
        // Untuk Complement Material: gunakan total_nilai_si jika tidak ada stock opname
        $stockAkhir = 0;

        if ($latestStockOpname) {
            // Jika ada stock opname, gunakan fisik (berlaku untuk semua material type)
            $stockAkhir = $latestStockOpname->fisik;
        } else {
            // Jika tidak ada stock opname, tentukan berdasarkan material_type
            if ($stockOpname->material_type === IncomingRawMaterial::class || $stockOpname->material_type === 'App\\Models\\IncomingRawMaterial') {
                $stockAkhir = $incomingMaterial->qty_roll;  // ✅ Raw Material → qty_roll
            } elseif ($stockOpname->material_type === IncomingComplementMaterial::class || $stockOpname->material_type === 'App\\Models\\IncomingComplementMaterial') {
                $stockAkhir = $incomingMaterial->total_nilai_si;  // ✅ Complement Material → total_nilai_si
            }
        }

        $stockOpnamesId = $latestStockOpname ? $latestStockOpname->id : null;

        // Kalkulasi berdasarkan material_type
        if ($stockOpname->material_type === IncomingRawMaterial::class || $stockOpname->material_type === 'App\\Models\\IncomingRawMaterial') {
            $this->calculateForRawMaterial($stockMaterial, $incomingMaterial, $stockAkhir, $stockOpnamesId);
        } elseif ($stockOpname->material_type === IncomingComplementMaterial::class || $stockOpname->material_type === 'App\\Models\\IncomingComplementMaterial') {
            $this->calculateForComplementMaterial($stockMaterial, $incomingMaterial, $stockAkhir, $stockOpnamesId);
        }
    }

    /**
     * Kalkulasi untuk Raw Material
     * 
     *
     */
    protected function calculateForRawMaterial($stockMaterial, $incomingMaterial, $stockAkhir, $stockOpnamesId)
    {
        // Hitung keluar roll
        $yard = (float) $incomingMaterial->yard;
        $keluarYard = (float) $stockMaterial->keluar_yard;
        $keluarRoll = $keluarYard - $yard;

        // Hitung sisa yard
        $jumlahRollSatuan = (float) $incomingMaterial->jumlah_roll_satuan;
        $sisaYard = $jumlahRollSatuan - $keluarYard;

        // Hitung sisa roll
        $sisaRoll = $yard > 0 ? round($sisaYard / $yard, 5) : 0;

        // Hitung total harga
        $hargaPerSatuan = (float) $incomingMaterial->harga_per_satuan;
        $totalHarga = round($sisaYard * $hargaPerSatuan, 2);

        // Hitung harga per satuan
        $calculatedHargaPerSatuan = $sisaYard > 0 ? round($totalHarga / $sisaYard, 2) : 0;

        // Update stock raw material
        $stockMaterial->update([
            'stock_opnames_id' => $stockOpnamesId,
            'keluar_roll' => $keluarRoll,
            'stock_akhir' => $stockAkhir,
            'sisa_yard' => $sisaYard,
            'sisa_roll' => $sisaRoll,
            'total_harga' => $totalHarga,
            'harga_per_satuan' => $calculatedHargaPerSatuan,
        ]);
    }

    /**
     * Kalkulasi untuk Complement Material
     * 
     * 
     */
    protected function calculateForComplementMaterial($stockMaterial, $incomingMaterial, $stockAkhir, $stockOpnamesId)
    {
        // Ambil data dari incoming complement material
        $hargaSatuanUkurSi = (float) $incomingMaterial->harga_satuan_ukur_si;
        $barangKeluar = (int) $stockMaterial->barang_keluar;

        // Hitung harga_barang_keluar
        $hargaBarangKeluar = round($hargaSatuanUkurSi * $barangKeluar, 2);

        // Hitung total_harga_stock_akhir
        $totalHargaStockAkhir = round($hargaSatuanUkurSi * $stockAkhir, 2);

        // Hitung harga_satuan_stock_akhir
        $hargaSatuanStockAkhir = $stockAkhir > 0 ? round($totalHargaStockAkhir / $stockAkhir, 2) : 0;

        // Update stock complement material
        $stockMaterial->update([
            'stock_opnames_id' => $stockOpnamesId,
            'stock_akhir' => $stockAkhir,
            'harga_barang_keluar' => $hargaBarangKeluar,
            'total_harga_stock_akhir' => $totalHargaStockAkhir,
            'harga_satuan_stock_akhir' => $hargaSatuanStockAkhir,
        ]);
    }

    /**
     * Dapatkan incoming material berdasarkan material_type
     * 
     * 
     */
    protected function getIncomingMaterial($stockMaterial, $materialType)
    {
        if ($materialType === IncomingRawMaterial::class || $materialType === 'App\\Models\\IncomingRawMaterial') {
            return IncomingRawMaterial::find($stockMaterial->incoming_raw_materials_id);
        } elseif ($materialType === IncomingComplementMaterial::class || $materialType === 'App\\Models\\IncomingComplementMaterial') {
            return IncomingComplementMaterial::find($stockMaterial->incoming_complement_materials_id);
        }

        return null;
    }

    /**
     * Dapatkan stock opname terbaru untuk suatu material
     * 
     *
     */
    protected function getLatestStockOpname($materialId, $materialType, $excludeId = null)
    {
        $query = StockOpname::where([
            'material_id' => $materialId,
            'material_type' => $materialType
        ]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->orderBy('created_at', 'desc')->first();
    }
}