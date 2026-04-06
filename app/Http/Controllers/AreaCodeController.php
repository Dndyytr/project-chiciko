<?php

namespace App\Http\Controllers;

use App\Models\AreaCode;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class AreaCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $areaCodes = AreaCode::when($search, function ($areaCodes) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $areaCodes = $areaCodes->where('nama_daerah', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.area-codes.index', compact('areaCodes'))
            ->with('i', ($page - 1) * $entries); // mengirim $areaCodes ke view area-codes.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.area-codes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_daerah' => 'required|string|max:255',
                'kode' => 'required|string|max:255|unique:area_codes,kode',
            ]);

            $areaCode = new AreaCode([
                'nama_daerah' => $request->nama_daerah,
                'kode' => $request->kode,
            ]);
            $areaCode->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('area-codes.index')->with('success', 'Area ' . $areaCode->nama_daerah . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('area-codes.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AreaCode $areaCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AreaCode $areaCode)
    {
        return view('settings.area-codes.edit', compact('areaCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AreaCode $areaCode)
    {
        try {
            $request->validate([
                'nama_daerah' => 'required|string|max:255',
                'kode' => 'required|string|max:255|unique:area_codes,kode,' . $areaCode->id,
            ]);

            $input = $request->only(['nama_daerah', 'kode']);
            $areaCode->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('area-codes.index')->with('success', 'Area ' . $areaCode->nama_daerah . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('area-codes.edit', $areaCode->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AreaCode $areaCode)
    {
        if ($areaCode) {
            $areaCode->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('area-codes.index')->with('success', 'Area ' . $areaCode->nama_daerah . ' deleted successfully.');
        } else {
            return redirect()->route('area-codes.index')->with('error', 'Area not found.');
        }
    }
}
