<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitInternal extends Model
{
    protected $fillable = [
        'nilai',
        'satuan_ukur',
    ];

    public function incomingComplementMaterials()
    {
        return $this->hasMany(IncomingComplementMaterial::class, 'unit_internals_id');
    }
}
