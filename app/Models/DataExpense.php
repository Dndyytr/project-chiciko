<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataExpense extends Model
{
    protected $fillable = [
        'tanggal_nota',
        'no_nota',
        'kategori',
        'keterangan',
        'harga_satuan',
        'kuantitas',
        'kredit',
    ];

    public function categoryExpense()
    {
        return $this->belongsTo(CategoryExpense::class, 'kategori', 'nama_kategori');
    }

    public function summaryExpenseDetails()
    {
        return $this->hasMany(SummaryExpenseDetail::class, 'data_expenses_id');
    }
}
