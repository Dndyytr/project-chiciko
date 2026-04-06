<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListAccountingEstimate extends Model
{
    protected $fillable = [
        'nama',
        'kode',
        'jenis',
        'penjelasan'
    ];

    public function DatabaseMaterials()
    {
        return $this->hasMany(DatabaseMaterial::class, 'kode_jp', 'kode');
    }
}
