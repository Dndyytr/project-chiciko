<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryExpense extends Model
{
    protected $fillable = [
        'tanggal_mulai',
        'tanggal_akhir',
        'total_keseluruhan',
    ];

    public function summaryExpenseDetails()
    {
        return $this->hasMany(SummaryExpenseDetail::class, 'summary_expenses_id');
    }
}
