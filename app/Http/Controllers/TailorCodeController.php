<?php

namespace App\Http\Controllers;

use App\Models\TailorCode;
use Illuminate\Http\Request;
use App\Models\CoordinatorCode;
use App\Models\AreaCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use App\Services\CacheManagementService;

class TailorCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $tailorCodes = TailorCode::when($search, function ($tailorCodes) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $tailorCodes = $tailorCodes->where('nama_koordinator', 'like', '%' . request()->search . '%')
                ->orWhere('kode_koordinator', 'like', '%' . request()->search . '%')
                ->orWhere('nama_daerah', 'like', '%' . request()->search . '%')
                ->orWhere('kode_daerah', 'like', '%' . request()->search . '%')
                ->orWhere('nama_penjahit', 'like', '%' . request()->search . '%')
                ->orWhere('no_urut', 'like', '%' . request()->search . '%')
                ->orWhere('kode_penjahit', 'like', '%' . request()->search . '%')
            ; // mencari nilai dari kolom kode_bahan_baku
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('settings.tailor-codes.index', compact('tailorCodes'))
            ->with('i', ($page - 1) * $entries); // mengirim $tailorCodes ke view database-materials.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.tailor-codes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_koordinator' => 'required|string|max:255',
                'kode_koordinator' => 'required|string|max:255|exists:coordinator_codes,kode',
                'nama_daerah' => 'required|string|max:255',
                'kode_daerah' => 'required|string|max:255|exists:area_codes,kode',
                'nama_penjahit' => 'required|string|max:255',
                'no_urut' => 'required|integer',
                'kode_penjahit' => 'required|string|max:255|unique:tailor_codes,kode_penjahit'
            ]);

            $tailorCode = new TailorCode([
                'nama_koordinator' => $request->nama_koordinator,
                'kode_koordinator' => $request->kode_koordinator,
                'nama_daerah' => $request->nama_daerah,
                'kode_daerah' => $request->kode_daerah,
                'nama_penjahit' => $request->nama_penjahit,
                'no_urut' => $request->no_urut,
                'kode_penjahit' => $request->kode_penjahit,
            ]);

            $tailorCode->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('tailor-codes.index')->with('success', 'Tailor Code ' . $tailorCode->nama_koordinator . ' created successfully');
        } catch (\Throwable $th) {
            //throw $th;
            // dd($request);
            return redirect()->route('tailor-codes.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TailorCode $tailorCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TailorCode $tailorCode)
    {
        return view('settings.tailor-codes.edit', compact('tailorCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TailorCode $tailorCode)
    {
        try {
            $request->validate([
                'nama_koordinator' => 'required|string|max:255',
                'kode_koordinator' => 'required|string|max:255|exists:coordinator_codes,kode',
                'nama_daerah' => 'required|string|max:255',
                'kode_daerah' => 'required|string|max:255|exists:area_codes,kode',
                'nama_penjahit' => 'required|string|max:255',
                'no_urut' => 'required|integer',
                'kode_penjahit' => 'required|string|max:255|unique:tailor_codes,kode_penjahit,' . $tailorCode->id
            ]);

            $input = $request->only([
                'nama_koordinator',
                'kode_koordinator',
                'nama_daerah',
                'kode_daerah',
                'nama_penjahit',
                'no_urut',
                'kode_penjahit'
            ]);

            $tailorCode->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('tailor-codes.index')->with('success', 'Tailor Code ' . $tailorCode->nama_koordinator . ' updated successfully');
        } catch (\Throwable $th) {
            //throw $th;
            // dd($request);
            return redirect()->route('tailor-codes.edit', $tailorCode->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TailorCode $tailorCode)
    {
        if ($tailorCode) {
            $tailorCode->delete();
            return redirect()->route('tailor-codes.index')->with('success', 'Tailor Code ' . $tailorCode->nama_koordinator . ' deleted successfully');
        } else {
            return redirect()->route('tailor-codes.index')->with('error', 'Tailor Code not found.');
        }
    }

    // API
    public function getAllData(Request $request)
    {
        // Pastikan user login dan punya hak akses
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'q' => 'nullable|string|min:2|max:50'
        ]);

        $search = $request->query('q', '');

        // Sanitize input
        $search = preg_replace('/[^a-zA-Z0-9\s]/', '', $search);

        // Gunakan cache untuk performa maksimal
        $cacheKey = "tailor_codes_" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            $result = [
                'coordinatorCodes' => [],
                'areaCodes' => [],
            ];

            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // Coordinator Codes
            $coordinatorCodeQuery = CoordinatorCode::select(['id', 'nama_koordinator', 'kode']);
            if ($hasSearch) {
                $coordinatorCodeQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama_koordinator', 'like', "%{$search}%");
                });
            } else {
                $coordinatorCodeQuery->orderBy('id');
            }
            $result['coordinatorCodes'] = $coordinatorCodeQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string) $item->id,
                    'text' => "{$item->nama_koordinator} | {$item->kode}",
                    'kode' => $item->kode,
                    'nama_koordinator' => $item->nama_koordinator,
                ];
            })->toArray();

            // area codes
            $areaCodeQuery = AreaCode::select(['id', 'nama_daerah', 'kode']);
            if ($hasSearch) {
                $areaCodeQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama_daerah', 'like', "%{$search}%");
                });
            } else {
                $areaCodeQuery->orderBy('id');
            }
            $result['areaCodes'] = $areaCodeQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string) $item->id,
                    'text' => "{$item->nama_daerah} | {$item->kode}",
                    'kode' => $item->kode,
                    'nama_daerah' => $item->nama_daerah,
                ];
            })->toArray();

            return $result;
        });

        // Baru wrap dengan response di luar cache
        return response()->json($data)->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
