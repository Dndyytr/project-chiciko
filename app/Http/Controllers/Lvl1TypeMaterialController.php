<?php

namespace App\Http\Controllers;

use App\Models\Lvl1TypeMaterial;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class Lvl1TypeMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $lvl1TypeMaterials = Lvl1TypeMaterial::when($search, function ($lvl1TypeMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $lvl1TypeMaterials = $lvl1TypeMaterials->where('nama', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.lvl1-type-materials.index', compact('lvl1TypeMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $lvl1TypeMaterials ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.lvl1-type-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:lvl1_type_materials,kode|max:255',
            ]);

            $lvl1TypeMaterial = new Lvl1TypeMaterial([
                'nama' => $request->input('nama'),
                'kode' => $request->input('kode'),
            ]);
            $lvl1TypeMaterial->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl1-type-materials.index')->with('success', 'Type Material ' . $lvl1TypeMaterial->nama . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('lvl1-type-materials.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lvl1TypeMaterial $lvl1TypeMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lvl1TypeMaterial $lvl1TypeMaterial)
    {
        return view('settings.lvl1-type-materials.edit', compact('lvl1TypeMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lvl1TypeMaterial $lvl1TypeMaterial)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:lvl1_type_materials,kode,' . $lvl1TypeMaterial->id . '|max:255',
            ]);

            $input = $request->only('nama', 'kode');

            $lvl1TypeMaterial->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl1-type-materials.index')->with('success', 'Type Material ' . $lvl1TypeMaterial->nama . ' updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('lvl1-type-materials.edit', $lvl1TypeMaterial->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lvl1TypeMaterial $lvl1TypeMaterial)
    {
        if ($lvl1TypeMaterial) {
            $lvl1TypeMaterial->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl1-type-materials.index')->with('success', 'Type Material ' . $lvl1TypeMaterial->nama . ' deleted successfully.');
        } else {
            return redirect()->route('lvl1-type-materials.index')->with('error', 'Type Material not found.');
        }
    }
}
