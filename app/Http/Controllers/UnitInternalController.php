<?php

namespace App\Http\Controllers;

use App\Models\UnitInternal;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class UnitInternalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $unitInternals = UnitInternal::when($search, function ($unitInternals) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $unitInternals = $unitInternals->where('nilai', 'like', '%' . request()->search . '%')
                ->orWhere('satuan_ukur', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.unit-internals.index', compact('unitInternals'))
            ->with('i', ($page - 1) * $entries); // mengirim $unitInternals ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.unit-internals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nilai' => 'required|integer|min:0',
                'satuan_ukur' => 'required|string|max:255',
            ]);

            $unitInternal = new UnitInternal([
                'nilai' => $request->input('nilai'),
                'satuan_ukur' => $request->input('satuan_ukur'),
            ]);
            $unitInternal->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('unit-internals.index')->with('success', 'Unit Internal ' . $unitInternal->satuan_ukur . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('unit-internals.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitInternal $unitInternal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitInternal $unitInternal)
    {
        return view('settings.unit-internals.edit', compact('unitInternal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitInternal $unitInternal)
    {
        try {
            $request->validate([
                'nilai' => 'required|integer|min:0',
                'satuan_ukur' => 'required|string|max:255',
            ]);

            $input = $request->only(['nilai', 'satuan_ukur']);
            $unitInternal->update($input);


            CacheManagementService::clearAppDataCache();
            return redirect()->route('unit-internals.index')->with('success', 'Unit Internal ' . $unitInternal->satuan_ukur . ' updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('unit-internals.edit', $unitInternal->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitInternal $unitInternal)
    {
        if ($unitInternal) {
            $unitInternal->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('unit-internals.index')->with('success', 'Unit Internal ' . $unitInternal->satuan_ukur . ' deleted successfully.');
        } else {
            return redirect()->route('unit-internals.index')->with('error', 'Unit Internal not found.');
        }
    }
}
