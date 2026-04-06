<?php

namespace App\Http\Controllers;

use App\Models\ListColorEstimate;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class ListColorEstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $listColorEstimates = ListColorEstimate::when($search, function ($listColorEstimates) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $listColorEstimates = $listColorEstimates->where('warna', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.list-color-estimates.index', compact('listColorEstimates'))
            ->with('i', ($page - 1) * $entries); // mengirim $listColorEstimates ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.list-color-estimates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'warna' => 'required|string|max:255',
                'kode' => 'required|string|unique:list_color_estimates,kode|max:255',
            ]);

            $listColorEstimate = new ListColorEstimate([
                'warna' => $request->warna,
                'kode' => $request->kode,
            ]);
            $listColorEstimate->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-color-estimates.index')->with('success', 'Color ' . $listColorEstimate->warna . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-color-estimates.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ListColorEstimate $listColorEstimate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListColorEstimate $listColorEstimate)
    {
        return view('settings.list-color-estimates.edit', compact('listColorEstimate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListColorEstimate $listColorEstimate)
    {
        try {
            $request->validate([
                'warna' => 'required|string|max:255',
                'kode' => 'required|string|unique:list_color_estimates,kode,' . $listColorEstimate->id . '|max:255',
            ]);

            $input = $request->only(['warna', 'kode']);
            $listColorEstimate->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-color-estimates.index')->with('success', 'Color ' . $listColorEstimate->warna . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-color-estimates.edit', $listColorEstimate->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListColorEstimate $listColorEstimate)
    {
        if ($listColorEstimate) {
            $listColorEstimate->delete();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-color-estimates.index')->with('success', 'Color ' . $listColorEstimate->warna . ' deleted successfully.');
        } else {
            return redirect()->route('list-color-estimates.index')->with('error', 'Color ' . $listColorEstimate->warna . ' could not be deleted.');
        }
    }
}
