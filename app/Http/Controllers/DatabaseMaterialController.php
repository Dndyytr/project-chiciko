<?php

namespace App\Http\Controllers;

use App\Models\ListAccountingEstimate;
use App\Models\ListColorEstimate;
use App\Models\Lvl1TypeMaterial;
use App\Models\Lvl2TypeMaterial;
use App\Models\Lvl3TypeMaterial;
use App\Models\DatabaseMaterial;
use Illuminate\Http\Request;
use App\Services\CacheManagementService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;

class DatabaseMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $databaseMaterials = DatabaseMaterial::when($search, function ($databaseMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $databaseMaterials = $databaseMaterials->where('name', 'like', '%' . request()->search . '%')
                ->orWhere('status', 'like', '%' . request()->search . '%')
                ->orWhere('kode_bahan', 'like', '%' . request()->search . '%')
                ->orWhere('text_jp', 'like', '%' . request()->search . '%')
                ->orWhere('kode_jp', 'like', '%' . request()->search . '%')
                ->orWhere('text_lvl1_jb', 'like', '%' . request()->search . '%')
                ->orWhere('kode_lvl1_jb', 'like', '%' . request()->search . '%')
                ->orWhere('text_lvl2_jb', 'like', '%' . request()->search . '%')
                ->orWhere('kode_lvl2_jb', 'like', '%' . request()->search . '%')
                ->orWhere('text_lvl3_jb', 'like', '%' . request()->search . '%')
                ->orWhere('kode_lvl3_jb', 'like', '%' . request()->search . '%')
                ->orWhere('text_warna', 'like', '%' . request()->search . '%')
                ->orWhere('kode_warna', 'like', '%' . request()->search . '%')
            ; // mencari nilai dari kolom kode_bahan_baku
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.database-materials.index', compact('databaseMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $databaseMaterials ke view database-materials.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.database-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|string|in:Aktif,Non Aktif',
                'kode_bahan' => 'required|string|max:100',
                'text_jp' => 'required|string|max:255',
                'kode_jp' => 'required|string|max:100',
                'text_lvl1_jb' => 'required|string|max:255',
                'kode_lvl1_jb' => 'required|string|max:100',
                'text_lvl2_jb' => 'required|string|max:255',
                'kode_lvl2_jb' => 'required|string|max:100',
                'text_lvl3_jb' => 'required|string|max:255',
                'kode_lvl3_jb' => 'required|string|max:100',
                'text_warna' => 'required|string|max:255',
                'kode_warna' => 'required|string|max:100',
            ]);

            $databaseMaterial = new DatabaseMaterial([
                'name' => $request->name,
                'status' => $request->status,
                'kode_bahan' => $request->kode_bahan,
                'text_jp' => $request->text_jp,
                'kode_jp' => $request->kode_jp,
                'text_lvl1_jb' => $request->text_lvl1_jb,
                'kode_lvl1_jb' => $request->kode_lvl1_jb,
                'text_lvl2_jb' => $request->text_lvl2_jb,
                'kode_lvl2_jb' => $request->kode_lvl2_jb,
                'text_lvl3_jb' => $request->text_lvl3_jb,
                'kode_lvl3_jb' => $request->kode_lvl3_jb,
                'text_warna' => $request->text_warna,
                'kode_warna' => $request->kode_warna,
            ]);
            $databaseMaterial->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('database-materials.index')->with('success', 'Sub Raw Material ' . $databaseMaterial->name . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('database-materials.create')->with(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DatabaseMaterial $databaseMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatabaseMaterial $databaseMaterial)
    {
        return view('admin.database-materials.edit', compact('databaseMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatabaseMaterial $databaseMaterial)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|string|in:Aktif,Non Aktif',
                'kode_bahan' => 'required|string|max:100',
                'text_jp' => 'required|string|max:255',
                'kode_jp' => 'required|string|max:100',
                'text_lvl1_jb' => 'required|string|max:255',
                'kode_lvl1_jb' => 'required|string|max:100',
                'text_lvl2_jb' => 'required|string|max:255',
                'kode_lvl2_jb' => 'required|string|max:100',
                'text_lvl3_jb' => 'required|string|max:255',
                'kode_lvl3_jb' => 'required|string|max:100',
                'text_warna' => 'required|string|max:255',
                'kode_warna' => 'required|string|max:100',
            ]);

            $input = $request->only([
                'name',
                'status',
                'kode_bahan',
                'text_jp',
                'kode_jp',
                'text_lvl1_jb',
                'kode_lvl1_jb',
                'text_lvl2_jb',
                'kode_lvl2_jb',
                'text_lvl3_jb',
                'kode_lvl3_jb',
                'text_warna',
                'kode_warna',
            ]);

            $databaseMaterial->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('database-materials.index')->with('success', 'Sub Raw Material ' . $databaseMaterial->name . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('database-materials.edit', $databaseMaterial->id)->with(['error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatabaseMaterial $databaseMaterial)
    {
        if ($databaseMaterial) {
            $databaseMaterial->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('database-materials.index')->with('success', 'Sub Raw Material ' . $databaseMaterial->name . ' deleted successfully.');
        } else {
            return redirect()->route('database-materials.index')->with('error', 'Sub Raw Material not found.');
        }
    }


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
        $cacheKey = "database_material_" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            $result = [
                'listAccountingEstimates' => [],
                'lvl1TypeMaterials' => [],
                'lvl2TypeMaterials' => [],
                'lvl3TypeMaterials' => [],
                'listColorEstimates' => [],
            ];

            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // List Accounting Estimates
            $listAccountingEstimateQuery = ListAccountingEstimate::select(['id', 'kode', 'nama']);
            if ($hasSearch) {
                $listAccountingEstimateQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
            } else {
                $listAccountingEstimateQuery->orderBy('id');
            }
            $result['listAccountingEstimates'] = $listAccountingEstimateQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string) $item->id,
                    'text' => $item->nama,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                ];
            })->toArray();

            // Lvl1 Type Materials
            $lvl1TypeMaterialQuery = Lvl1TypeMaterial::select(['id', 'kode', 'nama']);
            if ($hasSearch) {
                $lvl1TypeMaterialQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
            } else {
                $lvl1TypeMaterialQuery->orderBy('id');
            }
            $result['lvl1TypeMaterials'] = $lvl1TypeMaterialQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string) $item->id,
                    'text' => $item->nama,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                ];
            })->toArray();

            // Lvl2 Type Materials
            $lvl2TypeMaterialQuery = Lvl2TypeMaterial::select(['id', 'kode', 'nama']);
            if ($hasSearch) {
                $lvl2TypeMaterialQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
            } else {
                $lvl2TypeMaterialQuery->orderBy('id');
            }
            $result['lvl2TypeMaterials'] = $lvl2TypeMaterialQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string) $item->id,
                    'text' => $item->nama,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                ];
            })->toArray();

            // Lvl3 Type Materials
            $lvl3TypeMaterialQuery = Lvl3TypeMaterial::select(['id', 'kode', 'nama']);
            if ($hasSearch) {
                $lvl3TypeMaterialQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
            } else {
                $lvl3TypeMaterialQuery->orderBy('id');
            }
            $result['lvl3TypeMaterials'] = $lvl3TypeMaterialQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string) $item->id,
                    'text' => $item->nama,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                ];
            })->toArray();

            // List Color Estimates
            $listColorEstimateQuery = ListColorEstimate::select(['id', 'kode', 'warna']);
            if ($hasSearch) {
                $listColorEstimateQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('warna', 'like', "%{$search}%");
                });
            } else {
                $listColorEstimateQuery->orderBy('id');
            }
            $result['listColorEstimates'] = $listColorEstimateQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string) $item->id,
                    'text' => $item->warna,
                    'warna' => $item->warna,
                    'kode' => $item->kode,
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
