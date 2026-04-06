<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplementBasedNote extends Model
{
    protected $fillable = [
        'tanggal_nota',
        'no_kwitansi',
        'kode_supplier',
        'nama_supplier',
        'total_harga',
    ];

    // Relasi: ComplementBasedNote juga bisa langsung akses Supplier
    public function listSupplierEstimate()
    {
        return $this->belongsTo(ListSupplierEstimate::class, 'kode_supplier', 'kode');
    }
}
