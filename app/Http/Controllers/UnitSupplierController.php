<?php

namespace App\Http\Controllers;

use App\Models\UnitSupplier;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class UnitSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $unitSuppliers = UnitSupplier::when($search, function ($unitSuppliers) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $unitSuppliers = $unitSuppliers->where('nilai', 'like', '%' . request()->search . '%')
                ->orWhere('satuan_ukur', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.unit-suppliers.index', compact('unitSuppliers'))
            ->with('i', ($page - 1) * $entries); // mengirim $unitSuppliers ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.unit-suppliers.create');
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

            $unitSupplier = new UnitSupplier([
                'nilai' => $request->input('nilai'),
                'satuan_ukur' => $request->input('satuan_ukur'),
            ]);
            $unitSupplier->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('unit-suppliers.index')->with('success', 'Unit Supplier ' . $unitSupplier->satuan_ukur . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('unit-suppliers.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitSupplier $unitSupplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitSupplier $unitSupplier)
    {
        return view('settings.unit-suppliers.edit', compact('unitSupplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitSupplier $unitSupplier)
    {
        try {
            $request->validate([
                'nilai' => 'required|integer|min:0',
                'satuan_ukur' => 'required|string|max:255',
            ]);

            $input = $request->only(['nilai', 'satuan_ukur']);
            $unitSupplier->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('unit-suppliers.index')->with('success', 'Unit Supplier ' . $unitSupplier->satuan_ukur . ' updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('unit-suppliers.edit', $unitSupplier->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitSupplier $unitSupplier)
    {
        if ($unitSupplier) {
            $unitSupplier->delete();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('unit-suppliers.index')->with('success', 'Unit Supplier ' . $unitSupplier->satuan_ukur . ' deleted successfully.');
        } else {
            return redirect()->route('unit-suppliers.index')->with('error', 'Unit Supplier not found.');
        }
    }
}
