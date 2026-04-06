<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryExpenseDetail extends Model
{
    protected $fillable = [
        'summary_expenses_id',
        'data_expenses_id',
        'kategori',
        'total_uang_keluar',
        'urutan',
    ];

    public function categoryExpense()
    {
        return $this->belongsTo(CategoryExpense::class, 'kategori', 'nama_kategori');
    }

    public function summaryExpense()
    {
        return $this->belongsTo(SummaryExpense::class, 'summary_expenses_id');
    }

    public function dataExpense()
    {
        return $this->belongsTo(DataExpense::class, 'data_expenses_id');
    }
}