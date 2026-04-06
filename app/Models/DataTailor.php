<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTailor extends Model
{
    protected $fillable = [
        'nama_koordinator',
        'kode_koordinator',
        'nama_penjahit',
        'nama_daerah',
        'kode_penjahit',
    ];

    public function coordinatorCode()
    {
        return $this->belongsTo(CoordinatorCode::class, 'kode_koordinator', 'kode');
    }

    public function tailorCode()
    {
        return $this->belongsTo(TailorCode::class, 'kode_penjahit', 'kode_penjahit');
    }
}
