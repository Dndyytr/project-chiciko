<?php

namespace App\Http\Controllers;

use App\Models\DataWarehouse;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class DataWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $dataWarehouses = DataWarehouse::when($search, function ($dataWarehouses) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $dataWarehouses = $dataWarehouses->where('nama_gudang', 'like', '%' . request()->search . '%')
                ->orWhere('lokasi', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode_bahan_baku
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.data-warehouses.index', compact('dataWarehouses'))
            ->with('i', ($page - 1) * $entries); // mengirim $dataWarehouses ke view data-warehouses.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.data-warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_gudang' => 'required|string|max:255',
                'lokasi' => 'required|string',
            ]);

            $dataWarehouse = new DataWarehouse([
                'nama_gudang' => $request->nama_gudang,
                'lokasi' => $request->lokasi,
            ]);
            $dataWarehouse->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('data-warehouses.index')->with('success', 'Data Warehouse ' . $dataWarehouse->nama_gudang . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('data-warehouses.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DataWarehouse $dataWarehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataWarehouse $dataWarehouse)
    {
        return view('settings.data-warehouses.edit', compact('dataWarehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataWarehouse $dataWarehouse)
    {
        try {
            $request->validate([
                'nama_gudang' => 'required|string|max:255',
                'lokasi' => 'required|string',
            ]);

            $input = $request->only('nama_gudang', 'lokasi');

            $dataWarehouse->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('data-warehouses.index')->with('success', 'Data Warehouse ' . $dataWarehouse->nama_gudang . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('data-warehouses.edit', $dataWarehouse->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataWarehouse $dataWarehouse)
    {
        if ($dataWarehouse) {
            $dataWarehouse->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('data-warehouses.index')->with('success', 'Data Warehouse ' . $dataWarehouse->nama_gudang . ' deleted successfully.');
        } else {
            return redirect()->route('data-warehouses.index')->with('error', 'Data Warehouse not found.');
        }
    }
}
