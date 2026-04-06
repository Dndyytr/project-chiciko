<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListUnitMeasureEstimate extends Model
{
    protected $fillable = [
        'satuan',
        'arti',
        'kode'
    ];

    public function incomingComplementMaterials()
    {
        return $this->hasMany(IncomingComplementMaterial::class, 'satuan_ukur_sus', 'satuan');
    }

    public function complementBasedMaterials()
    {
        return $this->hasManyThrough(
            ComplementBasedMaterial::class,
            IncomingComplementMaterial::class,
            'satuan_ukur_sus',   // FK di incoming_complement_materials
            'complement_materials_id', // FK di complement_based_materials
            'satuan',            // PK di list_unit_measure_estimates
            'id'                 // PK di incoming_complement_materials
        );
    }

    public function incomingRawMaterials()
    {
        return $this->hasMany(IncomingRawMaterial::class, 'satuan_ukur', 'satuan');
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class, 'satuan', 'satuan');
    }
}
