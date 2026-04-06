<?php

namespace App\Http\Controllers;

use App\Models\ListAccountingEstimate;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class ListAccountingEstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $listAccountingEstimates = ListAccountingEstimate::when($search, function ($listAccountingEstimates) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $listAccountingEstimates = $listAccountingEstimates->where('nama', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%')
                ->orWhere('jenis', 'like', '%' . request()->search . '%')
                ->orWhere('penjelasan', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom nama
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.list-accounting-estimates.index', compact('listAccountingEstimates'))
            ->with('i', ($page - 1) * $entries); // mengirim $listAccountingEstimates ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.list-accounting-estimates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:list_accounting_estimates,kode|max:255',
                'jenis' => 'required|string|max:255',
                'penjelasan' => 'required|string',
            ]);

            $listAccountingEstimate = new ListAccountingEstimate([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'jenis' => $request->jenis,
                'penjelasan' => $request->penjelasan,
            ]);

            $listAccountingEstimate->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-accounting-estimates.index')->with('success', 'list ' . $listAccountingEstimate->nama . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-accounting-estimates.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ListAccountingEstimate $listAccountingEstimate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListAccountingEstimate $listAccountingEstimate)
    {
        return view('settings.list-accounting-estimates.edit', compact('listAccountingEstimate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListAccountingEstimate $listAccountingEstimate)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:list_accounting_estimates,kode,' . $listAccountingEstimate->id . '|max:255',
                'jenis' => 'required|string|max:255',
                'penjelasan' => 'required|string',
            ]);

            $input = $request->only(['nama', 'kode', 'jenis', 'penjelasan']);
            $listAccountingEstimate->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-accounting-estimates.index')->with('success', 'list ' . $listAccountingEstimate->nama . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-accounting-estimates.edit', $listAccountingEstimate->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListAccountingEstimate $listAccountingEstimate)
    {
        if ($listAccountingEstimate) {
            $listAccountingEstimate->delete();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-accounting-estimates.index')->with('success', 'List ' . $listAccountingEstimate->nama . ' deleted successfully.');
        } else {
            return redirect()->route('list-accounting-estimates.index')->with('error', 'List Accounting Estimate not found.');
        }
    }
}
