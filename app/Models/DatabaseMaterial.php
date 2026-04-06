<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Observers\DatabaseMaterialObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

class DatabaseMaterial extends Model
{
    protected $fillable = [
        'name',
        'status',
        'kode_bahan',
        'text_jp',
        'kode_jp',
        'text_lvl1_jb',
        'kode_lvl1_jb',
        'text_lvl2_jb',
        'kode_lvl2_jb',
        'text_lvl3_jb',
        'kode_lvl3_jb',
        'text_warna',
        'kode_warna',
    ];

    public function listAccountingEstimate()
    {
        return $this->belongsTo(ListAccountingEstimate::class, 'kode_jp', 'kode');
    }

    public function lvl1TypeMaterial()
    {
        return $this->belongsTo(Lvl1TypeMaterial::class, 'kode_lvl1_jb', 'kode');

    }

    public function lvl2TypeMaterial()
    {
        return $this->belongsTo(Lvl2TypeMaterial::class, 'kode_lvl2_jb', 'kode');
    }

    public function lvl3TypeMaterial()
    {
        return $this->belongsTo(Lvl3TypeMaterial::class, 'kode_lvl3_jb', 'kode');
    }

    public function listColorEstimate()
    {
        return $this->belongsTo(ListColorEstimate::class, 'kode_warna', 'kode');
    }

    public function incomingComplementMaterials()
    {
        return $this->hasMany(IncomingComplementMaterial::class, 'database_materials_id');
    }
}
