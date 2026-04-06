<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingComplementMaterial extends Model
{
    protected $fillable = [
        'database_materials_id',
        'unit_internals_id',
        'tanggal_nota',
        'no_kwitansi',
        'kode_supplier',
        'nama_supplier',
        'kode',
        'nama_barang_sesuai_nota',
        'jenis',
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

    public function supplier()
    {
        return $this->belongsTo(ListSupplierEstimate::class, 'kode_supplier', 'kode');
    }

    public function unitMeasure()
    {
        return $this->belongsTo(ListUnitMeasureEstimate::class, 'satuan_ukur_sus', 'satuan');
    }

    public function DatabaseMaterial()
    {
        return $this->belongsTo(DatabaseMaterial::class, 'database_materials_id');
    }

    public function unitInternal()
    {
        return $this->belongsTo(UnitInternal::class, 'unit_internals_id');
    }

    public function complementBasedMaterials()
    {
        return $this->hasMany(ComplementBasedMaterial::class, 'complement_materials_id');
    }

    public function stockOpnames()
    {
        return $this->morphMany(StockOpname::class, 'material');
    }

    public function stockComplementMaterials()
    {
        return $this->hasMany(StockComplementMaterial::class, 'incoming_complement_materials_id');
    }
}
