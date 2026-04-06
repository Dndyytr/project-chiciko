<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListSupplierEstimate extends Model
{
    protected $fillable = [
        'inisial',
        'nama_supplier',
        'kontak',
        'rekening',
        'kode',
        'alamat'
    ];

    public function incomingRawMaterials()
    {
        return $this->hasMany(IncomingRawMaterial::class, 'kode_supplier', 'kode');
    }

    // Relasi: Supplier punya banyak PurchaseBasedNotes lewat IncomingRawMaterials
    public function purchaseBasedNotes()
    {
        return $this->hasManyThrough(
            PurchaseBasedNote::class,
            IncomingRawMaterial::class,
            'kode_supplier',     // FK di incoming_raw_materials
            'no_kwitansi',       // FK di purchase_based_notes
            'kode',              // PK di list_supplier_estimates (kode)
            'no_kwitansi'        // PK di incoming_raw_materials
        );
    }

    // Relasi: Supplier punya banyak ComplementBasedNotes lewat IncomingComplementMaterials
    public function complementBasedNotes()
    {
        return $this->hasManyThrough(
            ComplementBasedNote::class,
            IncomingComplementMaterial::class,
            'kode_supplier',     // FK di incoming_complement_materials
            'no_kwitansi',       // FK di complement_based_notes
            'kode',              // PK di list_supplier_estimates (kode)
            'no_kwitansi'        // PK di incoming_complement_materials
        );
    }
}
