<?php

namespace App\Http\Controllers;

use App\Models\CoordinatorCode;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class CoordinatorCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $coordinatorCodes = CoordinatorCode::when($search, function ($coordinatorCodes) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $coordinatorCodes = $coordinatorCodes->where('nama_koordinator', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.coordinator-codes.index', compact('coordinatorCodes'))
            ->with('i', ($page - 1) * $entries); // mengirim $coordinatorCodes ke view coordinator-codes.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.coordinator-codes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_koordinator' => 'required|string|max:255',
                'kode' => 'required|string|max:255|unique:coordinator_codes,kode',
            ]);

            $coordinatorCode = new CoordinatorCode([
                'nama_koordinator' => $request->nama_koordinator,
                'kode' => $request->kode,
            ]);
            $coordinatorCode->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('coordinator-codes.index')->with('success', 'Coordinator ' . $coordinatorCode->nama_koordinator . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('coordinator-codes.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CoordinatorCode $coordinatorCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoordinatorCode $coordinatorCode)
    {
        return view('settings.coordinator-codes.edit', compact('coordinatorCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoordinatorCode $coordinatorCode)
    {
        try {
            $request->validate([
                'nama_koordinator' => 'required|string|max:255',
                'kode' => 'required|string|max:255|unique:coordinator_codes,kode,' . $coordinatorCode->id,
            ]);

            $input = $request->only(['nama_koordinator', 'kode']);
            $coordinatorCode->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('coordinator-codes.index')->with('success', 'Coordinator ' . $coordinatorCode->nama_koordinator . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('coordinator-codes.edit', $coordinatorCode->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoordinatorCode $coordinatorCode)
    {
        if ($coordinatorCode) {
            $coordinatorCode->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('coordinator-codes.index')->with('success', 'Coordinator ' . $coordinatorCode->nama_koordinator . ' deleted successfully.');
        } else {
            return redirect()->route('coordinator-codes.index')->with('error', 'Coordinator not found.');
        }
    }
}
