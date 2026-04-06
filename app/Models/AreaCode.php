<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaCode extends Model
{
    protected $fillable = [
        'nama_daerah',
        'kode',
    ];

    public function tailorCodes()
    {
        return $this->hasMany(TailorCode::class, 'kode_daerah', 'kode');
    }
}
