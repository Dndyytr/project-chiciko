<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplementBasedMaterial extends Model
{

    protected $fillable = [
        'complement_materials_id',
        'nama_barang_sesuai_nota',
        'jumlah_sus',
        'satuan_ukur_sus',
        'harga_satuan_sus',
        'jumlah_ksu',
        'satuan_ukur_ksu',
        'total_nilai_si',
        'satuan_ukur_si',
        'harga_satuan_ukur_si',
        'sub_total',
    ];

    public function incomingComplementMaterial()
    {
        return $this->belongsTo(IncomingComplementMaterial::class, 'complement_materials_id');
    }
}
