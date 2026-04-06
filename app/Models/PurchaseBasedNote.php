<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseBasedNote extends Model
{
    protected $fillable = [
        'tanggal_nota',
        'no_kwitansi',
        'kode_supplier',
        'nama_supplier',
        'qty_roll',
        'qty_yard',
        'jumlah',
    ];

    // Relasi: PurchaseBasedNote juga bisa langsung akses Supplier
    public function listSupplierEstimate()
    {
        return $this->belongsTo(ListSupplierEstimate::class, 'kode_supplier', 'kode');
    }
}
