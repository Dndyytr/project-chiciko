<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TailorCode extends Model
{
    protected $fillable = [
        'nama_koordinator',
        'kode_koordinator',
        'nama_daerah',
        'kode_daerah',
        'nama_penjahit',
        'no_urut',
        'kode_penjahit',
    ];

    public function coordinatorCode()
    {
        return $this->belongsTo(CoordinatorCode::class, 'kode_koordinator', 'kode');
    }

    public function areaCode()
    {
        return $this->belongsTo(AreaCode::class, 'kode_daerah', 'kode');
    }

    public function dataTailors()
    {
        return $this->hasMany(DataTailor::class, 'kode_penjahit', 'kode_penjahit');
    }
}
