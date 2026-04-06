<?php

namespace App\Http\Controllers;

use App\Models\Lvl2TypeMaterial;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class Lvl2TypeMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $lvl2TypeMaterials = Lvl2TypeMaterial::when($search, function ($lvl2TypeMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $lvl2TypeMaterials = $lvl2TypeMaterials->where('nama', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.lvl2-type-materials.index', compact('lvl2TypeMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $lvl2TypeMaterials ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.lvl2-type-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:lvl2_type_materials,kode|max:255',
            ]);

            $lvl2TypeMaterial = new Lvl2TypeMaterial([
                'nama' => $request->input('nama'),
                'kode' => $request->input('kode'),
            ]);
            $lvl2TypeMaterial->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl2-type-materials.index')->with('success', 'Type Material ' . $lvl2TypeMaterial->nama . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('lvl2-type-materials.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lvl2TypeMaterial $lvl2TypeMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lvl2TypeMaterial $lvl2TypeMaterial)
    {
        return view('settings.lvl2-type-materials.edit', compact('lvl2TypeMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lvl2TypeMaterial $lvl2TypeMaterial)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:lvl2_type_materials,kode,' . $lvl2TypeMaterial->id . '|max:255',
            ]);

            $input = $request->only('nama', 'kode');

            $lvl2TypeMaterial->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl2-type-materials.index')->with('success', 'Type Material ' . $lvl2TypeMaterial->nama . ' updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('lvl2-type-materials.edit', $lvl2TypeMaterial->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lvl2TypeMaterial $lvl2TypeMaterial)
    {
        if ($lvl2TypeMaterial) {
            $lvl2TypeMaterial->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl2-type-materials.index')->with('success', 'Type Material ' . $lvl2TypeMaterial->nama . ' deleted successfully.');
        } else {
            return redirect()->route('lvl2-type-materials.index')->with('error', 'Type Material not found.');
        }
    }
}
