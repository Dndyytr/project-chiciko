<?php

namespace App\Http\Controllers;

use App\Models\WorksheetAbbreviation;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;

class WorksheetAbbreviationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $worksheetAbbreviations = WorksheetAbbreviation::when($search, function ($worksheetAbbreviations) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $worksheetAbbreviations = $worksheetAbbreviations->where('singkatan', 'like', '%' . request()->search . '%')
                ->orWhere('lengkap', 'like', '%' . request()->search . '%');
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.worksheet-abbreviations.index', compact('worksheetAbbreviations'))
            ->with('i', ($page - 1) * $entries); // mengirim $worksheetAbbreviations ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.worksheet-abbreviations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'singkatan' => 'required|string|max:255',
                'lengkap' => 'required|string|max:255',
            ]);

            $worksheetAbbreviation = new WorksheetAbbreviation([
                'singkatan' => $request->input('singkatan'),
                'lengkap' => $request->input('lengkap'),
            ]);
            $worksheetAbbreviation->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('worksheet-abbreviations.index')->with('success', 'Worksheet Abbreviation ' . $worksheetAbbreviation->singkatan . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('worksheet-abbreviations.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorksheetAbbreviation $worksheetAbbreviation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorksheetAbbreviation $worksheetAbbreviation)
    {
        return view('settings.worksheet-abbreviations.edit', compact('worksheetAbbreviation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorksheetAbbreviation $worksheetAbbreviation)
    {
        try {
            $request->validate([
                'singkatan' => 'required|string|max:255',
                'lengkap' => 'required|string|max:255',
            ]);

            $input = $request->only('singkatan', 'lengkap');
            $worksheetAbbreviation->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('worksheet-abbreviations.index')->with('success', 'Worksheet Abbreviation ' . $worksheetAbbreviation->singkatan . ' updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('worksheet-abbreviations.edit', $worksheetAbbreviation->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorksheetAbbreviation $worksheetAbbreviation)
    {
        if ($worksheetAbbreviation) {
            $worksheetAbbreviation->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('worksheet-abbreviations.index')->with('success', 'Worksheet Abbreviation ' . $worksheetAbbreviation->singkatan . ' deleted successfully.');
        } else {
            return redirect()->route('worksheet-abbreviations.index')->with('error', 'Worksheet Abbreviation not found.');
        }
    }
}
