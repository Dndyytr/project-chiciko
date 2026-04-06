<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingRawMaterial extends Model
{
    protected $fillable = [
        'tanggal_nota',
        'no_kwitansi',
        'kode_supplier',
        'nama_supplier',
        'kode_barcode',
        'satuan_ukur',
        'nama_barang',
        'jenis_kain',
        'warna',
        'yard',
        'nama_barang_detail',
        'qty_roll',
        'kg_roll',
        'jumlah_roll_satuan',
        'harga_per_satuan',
        'harga_awal',
        'nominal_diskon',
        'total_diskon',
        'total_harga',
    ];


    public function listSupplierEstimate()
    {
        return $this->belongsTo(ListSupplierEstimate::class, 'kode_supplier', 'kode');
    }

    // Relasi: IncomingRawMaterial punya banyak PurchaseBasedRolls
    public function purchaseBasedRolls()
    {
        return $this->hasMany(PurchaseBasedRoll::class, 'incoming_raw_materials_id');
    }
    // Relasi: IncomingRawMaterial punya banyak PurchaseBasedYards
    public function purchaseBasedYards()
    {
        return $this->hasMany(PurchaseBasedYard::class, 'incoming_raw_materials_id');
    }

    public function stockOpnames()
    {
        return $this->morphMany(StockOpname::class, 'material');
    }

    public function listUnitMeasureEstimate()
    {
        return $this->belongsTo(ListUnitMeasureEstimate::class, 'satuan_ukur', 'satuan');
    }

    public function stockRawMaterials()
    {
        return $this->hasMany(StockRawMaterial::class, 'incoming_raw_materials_id');
    }

}
