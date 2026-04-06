<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lvl2TypeMaterial extends Model
{
    protected $fillable = [
        'nama',
        'kode',
    ];

    public function DatabaseMaterials()
    {
        return $this->hasMany(DatabaseMaterial::class, 'kode_lvl2_jb', 'kode');
    }
}
