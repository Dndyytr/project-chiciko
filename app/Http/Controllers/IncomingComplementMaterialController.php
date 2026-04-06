<?php

namespace App\Http\Controllers;

use App\Models\IncomingComplementMaterial;
use Illuminate\Http\Request;
use App\Models\ListSupplierEstimate;
use App\Models\DatabaseMaterial;
use App\Models\ListUnitMeasureEstimate;
use App\Models\UnitInternal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use App\Services\CacheManagementService;

class IncomingComplementMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $incomingComplementMaterials = IncomingComplementMaterial::when($search, function ($incomingComplementMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $incomingComplementMaterials = $incomingComplementMaterials->where('no_kwitansi', 'like', '%' . request()->search . '%')
                ->orWhere('kode_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('nama_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('kode', 'like', '%' . request()->search . '%')
                ->orWhere('nama_barang_sesuai_nota', 'like', '%' . request()->search . '%')
                ->orWhere('jenis', 'like', '%' . request()->search . '%')
                ->orWhere('jumlah_sus', 'like', '%' . request()->search . '%')
                ->orWhere('satuan_ukur_sus', 'like', '%' . request()->search . '%')
                ->orWhere('harga_satuan_sus', 'like', '%' . request()->search . '%')
                ->orWhere('jumlah_ksu', 'like', '%' . request()->search . '%')
                ->orWhere('harga_satuan_ksu', 'like', '%' . request()->search . '%')
                ->orWhere('total_nilai_si', 'like', '%' . request()->search . '%')
                ->orWhere('satuan_ukur_si', 'like', '%' . request()->search . '%')
                ->orWhere('harga_satuan_ukur_si', 'like', '%' . request()->search . '%')
                ->orWhere('sub_total', 'like', '%' . request()->search . '%')
            ; // mencari nilai dari kolom kode_bahan_baku
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.incoming-complement-materials.index', compact('incomingComplementMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $incomingComplementMaterials ke view incoming-complement-materials.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $listSupplierEstimates = ListSupplierEstimate::select('kode', 'nama_supplier')->get();
        // $databaseMaterials = DatabaseMaterial::select('id', 'kode_bahan', 'name')->get();
        // $listUnitMeasureEstimates = ListUnitMeasureEstimate::select('satuan')->get();
        // $unitInternals = UnitInternal::all();
        return view('admin.incoming-complement-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'database_materials_id' => 'required',
                'unit_internals_id' => 'required',
                'tanggal_nota' => 'required|date',
                'no_kwitansi' => 'required|string|max:255',
                'kode_supplier' => 'required|string|max:255',
                'nama_supplier' => 'required|string|max:255',
                'kode' => 'required|string|max:255',
                'nama_barang_sesuai_nota' => 'required|string|max:255',
                'jenis' => 'required|string|max:255',
                'jumlah_sus' => 'required|integer',
                'satuan_ukur_sus' => 'required|string|max:255',
                'harga_satuan_sus' => 'required|decimal:0,2|min:0',
                'jumlah_ksu' => 'required|integer',
                'satuan_ukur_ksu' => 'required|string|max:255',
                'total_nilai_si' => 'required|integer',
                'satuan_ukur_si' => 'required|string|max:255',
                'harga_satuan_ukur_si' => 'required|decimal:0,2|min:0',
                'sub_total' => 'required|decimal:0,2|min:0',

            ]);

            $incomingComplementMaterial = new IncomingComplementMaterial([
                'database_materials_id' => $request->database_materials_id,
                'unit_internals_id' => $request->unit_internals_id,
                'tanggal_nota' => $request->tanggal_nota,
                'no_kwitansi' => $request->no_kwitansi,
                'kode_supplier' => $request->kode_supplier,
                'nama_supplier' => $request->nama_supplier,
                'kode' => $request->kode,
                'nama_barang_sesuai_nota' => $request->nama_barang_sesuai_nota,
                'jenis' => $request->jenis,
                'jumlah_sus' => $request->jumlah_sus,
                'satuan_ukur_sus' => $request->satuan_ukur_sus,
                'harga_satuan_sus' => $request->harga_satuan_sus,
                'jumlah_ksu' => $request->jumlah_ksu,
                'satuan_ukur_ksu' => $request->satuan_ukur_ksu,
                'total_nilai_si' => $request->total_nilai_si,
                'satuan_ukur_si' => $request->satuan_ukur_si,
                'harga_satuan_ukur_si' => $request->harga_satuan_ukur_si,
                'sub_total' => $request->sub_total,
            ]);
            $incomingComplementMaterial->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('incoming-complement-materials.index')
                ->with('success', 'Incoming Complement Material ' . $incomingComplementMaterial->nama_barang_sesuai_nota . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('incoming-complement-materials.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomingComplementMaterial $incomingComplementMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingComplementMaterial $incomingComplementMaterial)
    {
        // $listSupplierEstimates = ListSupplierEstimate::select('kode', 'nama_supplier')->get();
        // $databaseMaterials = DatabaseMaterial::select('id', 'kode_bahan', 'name')->get();
        // $listUnitMeasureEstimates = ListUnitMeasureEstimate::select('satuan')->get();
        // $unitInternals = UnitInternal::all();
        return view('admin.incoming-complement-materials.edit', compact('incomingComplementMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingComplementMaterial $incomingComplementMaterial)
    {
        try {
            $request->validate([
                'database_materials_id' => 'required',
                'unit_internals_id' => 'required',
                'tanggal_nota' => 'required|date',
                'no_kwitansi' => 'required|string|max:255',
                'kode_supplier' => 'required|string|max:255',
                'nama_supplier' => 'required|string|max:255',
                'kode' => 'required|string|max:255',
                'nama_barang_sesuai_nota' => 'required|string|max:255',
                'jenis' => 'required|string|max:255',
                'jumlah_sus' => 'required|integer',
                'satuan_ukur_sus' => 'required|string|max:255',
                'harga_satuan_sus' => 'required|decimal:0,2|min:0',
                'jumlah_ksu' => 'required|integer',
                'satuan_ukur_ksu' => 'required|string|max:255',
                'total_nilai_si' => 'required|integer',
                'satuan_ukur_si' => 'required|string|max:255',
                'harga_satuan_ukur_si' => 'required|decimal:0,2|min:0',
                'sub_total' => 'required|decimal:0,2|min:0',

            ]);

            $input = $request->only([
                'database_materials_id',
                'unit_internals_id',
                'tanggal_nota',
                'no_kwitansi',
                'kode_supplier',
                'nama_supplier',
                'kode',
                'nama_barang_sesuai_nota',
                'jenis',
                'jumlah_sus',
                'satuan_ukur_sus',
                'harga_satuan_sus',
                'jumlah_ksu',
                'satuan_ukur_ksu',
                'total_nilai_si',
                'satuan_ukur_si',
                'harga_satuan_ukur_si',
                'sub_total',
            ]);
            $incomingComplementMaterial->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('incoming-complement-materials.index')
                ->with('success', 'Incoming Complement Material ' . $incomingComplementMaterial->nama_barang_sesuai_nota . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('incoming-complement-materials.edit', $incomingComplementMaterial->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingComplementMaterial $incomingComplementMaterial)
    {
        if ($incomingComplementMaterial) {
            $incomingComplementMaterial->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('incoming-complement-materials.index')->with('success', 'Incoming Complement Material ' . $incomingComplementMaterial->nama_barang_sesuai_nota . ' deleted successfully.');
        } else {
            return redirect()->route('incoming-complement-materials.index')->with('error', 'Incoming Complement Material not found.');
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
        $cacheKey = "all_complement_data_" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            $result = [
                'suppliers' => [],
                'materials' => [],
                'measures' => [],
                'units' => []
            ];

            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // Suppliers
            $supplierQuery = ListSupplierEstimate::select(['id', 'kode', 'nama_supplier']);
            if ($hasSearch) {
                $supplierQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama_supplier', 'like', "%{$search}%");
                });
            } else {
                // Jika tidak ada search, ambil 50 data pertama (A-Z)
                $supplierQuery->orderBy('id');
            }
            $result['suppliers'] = $supplierQuery->limit(50)->get()->map(function ($s) {
                return [
                    'value' => (string) $s->id,
                    'text' => $s->nama_supplier,
                    'kode' => $s->kode,
                    'nama_supplier' => $s->nama_supplier
                ];
            })->toArray();

            // Materials
            $materialQuery = DatabaseMaterial::select(['id', 'name', 'kode_bahan']);
            if ($hasSearch) {
                $materialQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('kode_bahan', 'like', "%{$search}%");
                });
            } else {
                $materialQuery->orderBy('id');
            }
            $result['materials'] = $materialQuery->limit(50)->get()->map(function ($m) {
                return [
                    'value' => $m->id,
                    'text' => $m->name,
                    'name' => $m->name,
                    'kode_bahan' => $m->kode_bahan
                ];
            })->toArray();

            // Measures
            $measureQuery = ListUnitMeasureEstimate::select(['id', 'satuan']);
            if ($hasSearch) {
                $measureQuery->where(function ($q) use ($search) {
                    $q->where('satuan', 'like', "%{$search}%");
                });
            } else {
                $measureQuery->orderBy('id');
            }
            $result['measures'] = $measureQuery->limit(50)->get()->map(function ($m) {
                return [
                    'value' => (string) $m->id,
                    'text' => $m->satuan,
                    'satuan' => $m->satuan
                ];
            })->toArray();

            // Units
            $unitQuery = UnitInternal::select(['id', 'nilai', 'satuan_ukur']);
            if ($hasSearch) {
                $unitQuery->where(function ($q) use ($search) {
                    $q->where('nilai', 'like', "%{$search}%")
                        ->orWhere('satuan_ukur', 'like', "%{$search}%");
                });
            } else {
                $unitQuery->orderBy('id');
            }
            $result['units'] = $unitQuery->limit(50)->get()->map(function ($u) {
                return [
                    'value' => $u->id,
                    'text' => "{$u->nilai} - {$u->satuan_ukur}",
                    'nilai' => $u->nilai,
                    'satuan_ukur' => $u->satuan_ukur
                ];
            })->toArray();

            return $result; // ✅ HANYA DATA ARRAY, BUKAN RESPONSE
        });


        // Baru wrap dengan response di luar cache
        return response()->json($data)->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
