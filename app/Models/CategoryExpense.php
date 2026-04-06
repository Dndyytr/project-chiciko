<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryExpense extends Model
{
    protected $fillable = ['nama_kategori'];

    public function dataExpenses()
    {
        return $this->hasMany(DataExpense::class, 'kategori', 'nama_kategori');
    }

    // Relasi: category expense punya banyak summary expense detail lewat data expense
    public function summaryExpenseDetails()
    {
        return $this->hasManyThrough(
            SummaryExpenseDetail::class,
            DataExpense::class,
            'kategori', // FK di data_expenses
            'data_expenses_id', // FK di summary_expense_details
            'nama_kategori', // PK di category_expenses
            'id' // PK di data_expenses
        );
    }
}
