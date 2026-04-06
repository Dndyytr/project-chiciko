<?php

namespace App\Http\Controllers;

use App\Models\CategoryExpense;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class CategoryExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $categoryExpenses = CategoryExpense::when($search, function ($categoryExpenses) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $categoryExpenses = $categoryExpenses->where('nama_kategori', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom nama
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.category-expenses.index', compact('categoryExpenses'))
            ->with('i', ($page - 1) * $entries); // mengirim $categoryExpenses ke view expense-categorys.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.category-expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|unique:category_expenses,nama_kategori|max:255',
            ]);

            $categoryExpense = new CategoryExpense([
                'nama_kategori' => $request->nama_kategori,
            ]);
            $categoryExpense->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('category-expenses.index')->with('success', 'Category Expense ' . $categoryExpense->nama_kategori . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('category-expenses.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryExpense $categoryExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryExpense $categoryExpense)
    {
        return view('settings.category-expenses.edit', compact('categoryExpense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryExpense $categoryExpense)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|unique:category_expenses,nama_kategori,' . $categoryExpense->id . '|max:255',
            ]);

            $input = $request->only([
                'nama_kategori',
            ]);

            $categoryExpense->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('category-expenses.index')->with('success', 'Category Expense ' . $categoryExpense->nama_kategori . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('category-expenses.edit', $categoryExpense->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryExpense $categoryExpense)
    {
        if ($categoryExpense) {
            $categoryExpense->delete();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('category-expenses.index')->with('success', 'Category Expense ' . $categoryExpense->nama_kategori . ' deleted successfully.');
        } else {
            return redirect()->route('category-expenses.index')->with('error', 'Category Expense not found.');
        }
    }
}
