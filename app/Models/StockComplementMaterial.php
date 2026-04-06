<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockComplementMaterial extends Model
{
    protected $fillable = [
        'incoming_complement_materials_id',
        'stock_opnames_id',
        'nama_item',
        'barang_keluar',
        'stock_akhir',
        'harga_barang_keluar',
        'total_harga_stock_akhir',
        'harga_satuan_stock_akhir'
    ];

    public function incomingComplementMaterial()
    {
        return $this->belongsTo(IncomingComplementMaterial::class, 'incoming_complement_materials_id');
    }

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class, 'stock_opnames_id');
    }
}
