<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseBasedYard extends Model
{
    protected $fillable = [
        'incoming_raw_materials_id',
        'kode_barcode',
        'nama_barang',
        'jenis_kain',
        'warna',
        'qty_roll',
        'yard_per_roll',
        'kg_per_roll',
        'jumlah_roll_satuan',
        'total_harga',
        'harga_per_satuan',
    ];

    public function incomingRawMaterial()
    {
        return $this->belongsTo(IncomingRawMaterial::class, 'incoming_raw_materials_id');
    }
}
