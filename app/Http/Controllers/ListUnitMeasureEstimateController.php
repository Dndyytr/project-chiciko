<?php

namespace App\Http\Controllers;

use App\Models\ListUnitMeasureEstimate;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class ListUnitMeasureEstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $listUnitMeasureEstimates = ListUnitMeasureEstimate::when($search, function ($listUnitMeasureEstimates) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $listUnitMeasureEstimates = $listUnitMeasureEstimates->where('satuan', 'like', '%' . request()->search . '%')
                ->orWhere('arti', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.list-unit-measure-estimates.index', compact('listUnitMeasureEstimates'))
            ->with('i', ($page - 1) * $entries); // mengirim $listUnitMeasureEstimates ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.list-unit-measure-estimates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'satuan' => 'required|string|unique:list_unit_measure_estimates,satuan|max:255',
                'arti' => 'required|string|unique:list_unit_measure_estimates,arti|max:255',
                'kode' => 'required|string|unique:list_unit_measure_estimates,kode|max:255'
            ]);

            $listUnitMeasureEstimate = new ListUnitMeasureEstimate([
                'satuan' => $request->satuan,
                'arti' => $request->arti,
                'kode' => $request->kode,
            ]);

            $listUnitMeasureEstimate->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-unit-measure-estimates.index')->with('success', 'Unit Measure ' . $listUnitMeasureEstimate->arti . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-unit-measure-estimates.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ListUnitMeasureEstimate $listUnitMeasureEstimate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListUnitMeasureEstimate $listUnitMeasureEstimate)
    {
        return view('settings.list-unit-measure-estimates.edit', compact('listUnitMeasureEstimate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListUnitMeasureEstimate $listUnitMeasureEstimate)
    {
        try {
            $request->validate([
                'satuan' => 'required|string|unique:list_unit_measure_estimates,satuan,' . $listUnitMeasureEstimate->id . '|max:255',
                'arti' => 'required|string|unique:list_unit_measure_estimates,arti,' . $listUnitMeasureEstimate->id . '|max:255',
                'kode' => 'required|string|unique:list_unit_measure_estimates,kode,' . $listUnitMeasureEstimate->id . '|max:255'
            ]);

            $listUnitMeasureEstimate->update($request->only(['satuan', 'arti', 'kode']));

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-unit-measure-estimates.index')->with('success', 'Unit Measure ' . $listUnitMeasureEstimate->arti . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-unit-measure-estimates.edit', $listUnitMeasureEstimate->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListUnitMeasureEstimate $listUnitMeasureEstimate)
    {
        if ($listUnitMeasureEstimate) {
            $listUnitMeasureEstimate->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-unit-measure-estimates.index')->with('success', 'Unit Measure ' . $listUnitMeasureEstimate->arti . ' deleted successfully.');
        } else {
            return redirect()->route('list-unit-measure-estimates.index')->with('error', 'Unit Measure not found.');
        }
    }
}
