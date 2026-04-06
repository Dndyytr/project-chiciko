<?php

namespace App\Http\Controllers;

use App\Models\Lvl3TypeMaterial;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class Lvl3TypeMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $lvl3TypeMaterials = Lvl3TypeMaterial::when($search, function ($lvl3TypeMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $lvl3TypeMaterials = $lvl3TypeMaterials->where('nama', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.lvl3-type-materials.index', compact('lvl3TypeMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $lvl3TypeMaterials ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.lvl3-type-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:lvl3_type_materials,kode|max:255',
            ]);

            $lvl3TypeMaterial = new Lvl3TypeMaterial([
                'nama' => $request->input('nama'),
                'kode' => $request->input('kode'),
            ]);
            $lvl3TypeMaterial->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl3-type-materials.index')->with('success', 'Type Material ' . $lvl3TypeMaterial->nama . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('lvl3-type-materials.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lvl3TypeMaterial $lvl3TypeMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lvl3TypeMaterial $lvl3TypeMaterial)
    {
        return view('settings.lvl3-type-materials.edit', compact('lvl3TypeMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lvl3TypeMaterial $lvl3TypeMaterial)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:lvl3_type_materials,kode,' . $lvl3TypeMaterial->id . '|max:255',
            ]);

            $input = $request->only('nama', 'kode');

            $lvl3TypeMaterial->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl3-type-materials.index')->with('success', 'Type Material ' . $lvl3TypeMaterial->nama . ' updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('lvl3-type-materials.edit', $lvl3TypeMaterial->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lvl3TypeMaterial $lvl3TypeMaterial)
    {
        if ($lvl3TypeMaterial) {
            $lvl3TypeMaterial->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('lvl3-type-materials.index')->with('success', 'Type Material ' . $lvl3TypeMaterial->nama . ' deleted successfully.');
        } else {
            return redirect()->route('lvl3-type-materials.index')->with('error', 'Type Material not found.');
        }
    }
}
