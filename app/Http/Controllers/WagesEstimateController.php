<?php

namespace App\Http\Controllers;

use App\Models\WagesEstimate;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class WagesEstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $wagesEstimates = WagesEstimate::when($search, function ($wagesEstimates) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $wagesEstimates = $wagesEstimates->where('nama', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%')
                ->orWhere('helper', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.wages-estimates.index', compact('wagesEstimates'))
            ->with('i', ($page - 1) * $entries); // mengirim $wagesEstimates ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.wages-estimates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:wages_estimates,kode|max:255',
                'helper' => 'required|string|max:255',
            ]);


            $wagesEstimate = new WagesEstimate([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'helper' => $request->helper,
            ]);

            $wagesEstimate->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('wages-estimates.index')->with('success', 'Wages ' . $wagesEstimate->nama . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('wages-estimates.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WagesEstimate $wagesEstimate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WagesEstimate $wagesEstimate)
    {
        return view('settings.wages-estimates.edit', compact('wagesEstimate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WagesEstimate $wagesEstimate)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:wages_estimates,kode,' . $wagesEstimate->id . '|max:255',
                'helper' => 'required|string|max:255',
            ]);


            $input = $request->only(['nama', 'kode', 'helper']);

            $wagesEstimate->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('wages-estimates.index')->with('success', 'Wages ' . $wagesEstimate->nama . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('wages-estimates.edit', $wagesEstimate->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WagesEstimate $wagesEstimate)
    {
        if ($wagesEstimate) {
            $wagesEstimate->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('wages-estimates.index')->with('success', 'Wages ' . $wagesEstimate->nama . ' deleted successfully.');
        } else {
            return redirect()->route('wages-estimates.index')->with('error', 'Wages not found.');
        }
    }
}
