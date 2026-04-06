<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoordinatorCode extends Model
{
    protected $fillable = [
        'nama_koordinator',
        'kode',
    ];

    public function tailorCodes()
    {
        return $this->hasMany(TailorCode::class, 'kode_koordinator', 'kode');
    }

    public function dataTailors()
    {
        return $this->hasMany(DataTailor::class, 'kode_koordinator', 'kode');
    }
}
