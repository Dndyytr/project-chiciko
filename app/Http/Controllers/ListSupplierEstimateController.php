<?php

namespace App\Http\Controllers;

use App\Models\ListSupplierEstimate;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class ListSupplierEstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $listSupplierEstimates = ListSupplierEstimate::when($search, function ($listSupplierEstimates) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $listSupplierEstimates = $listSupplierEstimates->where('inisial', 'like', '%' . request()->search . '%')
                ->orWhere('nama_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('alamat', 'like', '%' . request()->search . '%')
                ->orWhere('kontak', 'like', '%' . request()->search . '%')
                ->orWhere('rekening', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.list-supplier-estimates.index', compact('listSupplierEstimates'))
            ->with('i', ($page - 1) * $entries); // mengirim $listSupplierEstimates ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.list-supplier-estimates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'inisial' => 'required|string|unique:list_supplier_estimates,inisial|max:255',
                'nama_supplier' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kontak' => 'required|string|max:255',
                'rekening' => 'required|string|max:255',
                'kode' => 'required|string|unique:list_supplier_estimates,kode|max:255',
            ]);

            $listSupplierEstimate = new ListSupplierEstimate([
                'inisial' => $request->inisial,
                'nama_supplier' => $request->nama_supplier,
                'alamat' => $request->alamat,
                'kontak' => $request->kontak,
                'rekening' => $request->rekening,
                'kode' => $request->kode,
            ]);

            $listSupplierEstimate->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-supplier-estimates.index')->with('success', $listSupplierEstimate->nama_supplier . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-supplier-estimates.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ListSupplierEstimate $listSupplierEstimate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListSupplierEstimate $listSupplierEstimate)
    {
        return view('settings.list-supplier-estimates.edit', compact('listSupplierEstimate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListSupplierEstimate $listSupplierEstimate)
    {
        try {
            $request->validate([
                'inisial' => 'required|string|unique:list_supplier_estimates,inisial,' . $listSupplierEstimate->id . '|max:255',
                'nama_supplier' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kontak' => 'required|string|max:255',
                'rekening' => 'required|string|max:255',
                'kode' => 'required|string|unique:list_supplier_estimates,kode,' . $listSupplierEstimate->id . '|max:255',
            ]);

            $input = $request->only([
                'inisial',
                'nama_supplier',
                'alamat',
                'kontak',
                'rekening',
                'kode',
            ]);

            $listSupplierEstimate->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-supplier-estimates.index')->with('success', $listSupplierEstimate->nama_supplier . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('list-supplier-estimates.edit', $listSupplierEstimate->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListSupplierEstimate $listSupplierEstimate)
    {
        if ($listSupplierEstimate) {
            $listSupplierEstimate->delete();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('list-supplier-estimates.index')->with('success', $listSupplierEstimate->nama_supplier . ' deleted successfully.');
        } else {
            return redirect()->route('list-supplier-estimates.index')->with('error', 'Failed to delete ' . $listSupplierEstimate->nama_supplier . '.');
        }
    }
}
