<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataWarehouse extends Model
{
    protected $fillable = [
        'nama_gudang',
        'lokasi',
    ];

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class, 'data_warehouses_id');
    }
}
