<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListColorEstimate extends Model
{
    protected $fillable = [
        'warna',
        'kode',
    ];

    public function DatabaseMaterials()
    {
        return $this->hasMany(DatabaseMaterial::class, 'kode_warna', 'kode');
    }
}
