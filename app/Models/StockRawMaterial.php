<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRawMaterial extends Model
{
    protected $fillable = [
        'incoming_raw_materials_id',
        'nama_item',
        'stock_opnames_id',
        'keluar_roll',
        'keluar_yard',
        'stock_akhir',
        'sisa_roll',
        'sisa_yard',
        'total_harga',
        'harga_per_satuan',
    ];

    public function incomingRawMaterial()
    {
        return $this->belongsTo(IncomingRawMaterial::class, 'incoming_raw_materials_id');
    }

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class, 'stock_opnames_id');
    }
}
