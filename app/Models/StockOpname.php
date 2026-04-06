<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [
        'data_warehouses_id',
        'material_id',
        'material_type',
        'nama_gudang',
        'kode_item',
        'kode_barcode',
        'nama_item',
        'satuan',
        'buku',
        'fisik',
        'selisih',
    ];

    public function dataWarehouses()
    {
        return $this->belongsTo(DataWarehouse::class, 'data_warehouses_id');
    }

    public function material()
    {
        return $this->morphTo();
    }

    public function listUnitMeasureEstimate()
    {
        return $this->belongsTo(ListUnitMeasureEstimate::class, 'satuan', 'satuan');
    }

    public function stockRawMaterials()
    {
        return $this->hasMany(StockRawMaterial::class, 'stock_opnames_id');
    }
    public function stockComplementMaterials()
    {
        return $this->hasMany(StockComplementMaterial::class, 'stock_opnames_id');
    }
}
