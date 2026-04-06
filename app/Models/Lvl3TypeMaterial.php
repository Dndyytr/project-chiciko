<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lvl3TypeMaterial extends Model
{
    protected $fillable = [
        'nama',
        'kode',
    ];

    public function DatabaseMaterials()
    {
        return $this->hasMany(DatabaseMaterial::class, 'kode_lvl3_jb', 'kode');
    }
}
