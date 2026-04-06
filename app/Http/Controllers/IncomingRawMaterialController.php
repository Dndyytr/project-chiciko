<?php

namespace App\Http\Controllers;

use App\Models\IncomingRawMaterial;
use Illuminate\Http\Request;
use App\Models\ListSupplierEstimate;
use App\Models\ListUnitMeasureEstimate;
use App\Services\CacheManagementService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;

class IncomingRawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $incomingRawMaterials = IncomingRawMaterial::when($search, function ($incomingRawMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $incomingRawMaterials = $incomingRawMaterials->where('no_kwitansi', 'like', '%' . request()->search . '%')
                ->orWhere('kode_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('nama_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('kode_barcode', 'like', '%' . request()->search . '%')
                ->orWhere('nama_barang', 'like', '%' . request()->search . '%')
                ->orWhere('jenis_kain', 'like', '%' . request()->search . '%')
                ->orWhere('warna', 'like', '%' . request()->search . '%')
                ->orWhere('yard', 'like', '%' . request()->search . '%')
                ->orWhere('nama_barang_detail', 'like', '%' . request()->search . '%')
                ->orWhere('qty_roll', 'like', '%' . request()->search . '%')
                ->orWhere('kg_roll', 'like', '%' . request()->search . '%')
                ->orWhere('jumlah_roll_satuan', 'like', '%' . request()->search . '%')
                ->orWhere('harga_per_satuan', 'like', '%' . request()->search . '%')
                ->orWhere('harga_awal', 'like', '%' . request()->search . '%')
                ->orWhere('nominal_diskon', 'like', '%' . request()->search . '%')
                ->orWhere('total_diskon', 'like', '%' . request()->search . '%')
                ->orWhere('total_harga', 'like', '%' . request()->search . '%')
            ; // mencari nilai dari kolom kode_bahan_baku
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.incoming-raw-materials.index', compact('incomingRawMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $incomingRawMaterials ke view incoming-raw-materials.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.incoming-raw-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal_nota' => 'required|date',
                'no_kwitansi' => 'required|string|max:255',
                'kode_supplier' => 'required|string|max:255',
                'nama_supplier' => 'required|string|max:255',
                'kode_barcode' => 'required|string|max:255',
                'satuan_ukur' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
                'jenis_kain' => 'required|string|max:255',
                'warna' => 'required|string|max:255',
                'yard' => 'required|integer',
                'nama_barang_detail' => 'required|string',
                'qty_roll' => 'required|integer',
                'kg_roll' => 'required|decimal:0,2|min:0',
                'jumlah_roll_satuan' => 'required|integer',
                'harga_per_satuan' => 'required|decimal:0,2|min:0',
                'harga_awal' => 'required|decimal:0,2|min:0',
                'nominal_diskon' => 'required|decimal:0,2|min:0',
                'total_diskon' => 'required|decimal:0,2|min:0',
                'total_harga' => 'required|decimal:0,2|min:0',
            ]);

            $incomingRawMaterials = new IncomingRawMaterial([
                'tanggal_nota' => $request->tanggal_nota,
                'no_kwitansi' => $request->no_kwitansi,
                'kode_supplier' => $request->kode_supplier,
                'nama_supplier' => $request->nama_supplier,
                'kode_barcode' => $request->kode_barcode,
                'satuan_ukur' => $request->satuan_ukur,
                'nama_barang' => $request->nama_barang,
                'jenis_kain' => $request->jenis_kain,
                'warna' => $request->warna,
                'yard' => $request->yard,
                'nama_barang_detail' => $request->nama_barang_detail,
                'qty_roll' => $request->qty_roll,
                'kg_roll' => $request->kg_roll,
                'jumlah_roll_satuan' => $request->jumlah_roll_satuan,
                'harga_per_satuan' => $request->harga_per_satuan,
                'harga_awal' => $request->harga_awal,
                'nominal_diskon' => $request->nominal_diskon,
                'total_diskon' => $request->total_diskon,
                'total_harga' => $request->total_harga,
            ]);

            $incomingRawMaterials->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('incoming-raw-materials.index')->with('success', 'Incoming Raw Material ' . $incomingRawMaterials->nama_barang . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('incoming-raw-materials.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomingRawMaterial $incomingRawMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingRawMaterial $incomingRawMaterial)
    {
        return view('admin.incoming-raw-materials.edit', compact('incomingRawMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingRawMaterial $incomingRawMaterial)
    {
        try {
            $request->validate([
                'tanggal_nota' => 'required|date',
                'no_kwitansi' => 'required|string|max:255',
                'kode_supplier' => 'required|string|max:255',
                'nama_supplier' => 'required|string|max:255',
                'kode_barcode' => 'required|string|max:255',
                'satuan_ukur' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
                'jenis_kain' => 'required|string|max:255',
                'warna' => 'required|string|max:255',
                'yard' => 'required|integer',
                'nama_barang_detail' => 'required|string',
                'qty_roll' => 'required|integer',
                'kg_roll' => 'required|decimal:0,2|min:0',
                'jumlah_roll_satuan' => 'required|integer',
                'harga_per_satuan' => 'required|decimal:0,2|min:0',
                'harga_awal' => 'required|decimal:0,2|min:0',
                'nominal_diskon' => 'required|decimal:0,2|min:0',
                'total_diskon' => 'required|decimal:0,2|min:0',
                'total_harga' => 'required|decimal:0,2|min:0',
            ]);

            $input = $request->only([
                'tanggal_nota',
                'no_kwitansi',
                'kode_supplier',
                'nama_supplier',
                'kode_barcode',
                'satuan_ukur',
                'nama_barang',
                'jenis_kain',
                'warna',
                'yard',
                'nama_barang_detail',
                'qty_roll',
                'kg_roll',
                'jumlah_roll_satuan',
                'harga_per_satuan',
                'harga_awal',
                'nominal_diskon',
                'total_diskon',
                'total_harga',
            ]);

            $incomingRawMaterial->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('incoming-raw-materials.index')->with('success', 'Incoming Raw Material ' . $incomingRawMaterial->nama_barang . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('incoming-raw-materials.edit', $incomingRawMaterial->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingRawMaterial $incomingRawMaterial)
    {
        try {
            if ($incomingRawMaterial) {
                $incomingRawMaterial->delete();
                CacheManagementService::clearAppDataCache();
                return redirect()->route('incoming-raw-materials.index')->with('success', 'Incoming Raw Material ' . $incomingRawMaterial->nama_barang . ' deleted successfully.');
            } else {
                return redirect()->route('incoming-raw-materials.index')->with('error', 'Incoming Raw Material not found.');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('incoming-raw-materials.index')->with('error', $th->getMessage());
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
        $cacheKey = "all_raw_data_" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            $result = [
                'listSupplierEstimates' => [],
                'listUnitMeasureEstimates' => [],
            ];

            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // list supplier estimates
            $listSupplierEstimatesQuery = ListSupplierEstimate::select(['id', 'kode', 'nama_supplier']);
            if ($hasSearch) {
                $listSupplierEstimatesQuery->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama_supplier', 'like', "%{$search}%");
                });
            } else {
                // Jika tidak ada search, ambil 50 data pertama (A-Z)
                $listSupplierEstimatesQuery->orderBy('id');
            }
            $result['listSupplierEstimates'] = $listSupplierEstimatesQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string)$item->id,
                    'text' => $item->nama_supplier,
                    'kode' => $item->kode,
                    'nama_supplier' => $item->nama_supplier,
                ];
            })->toArray();

            // list unit measure estimates
            $listUnitMeasureEstimatesQuery = ListUnitMeasureEstimate::select(['id', 'satuan']);
            if ($hasSearch) {
                $listUnitMeasureEstimatesQuery->where(function ($q) use ($search) {
                    $q->where('satuan', 'like', "%{$search}%");
                });
            } else {
                // Jika tidak ada search, ambil 50 data pertama (A-Z)
                $listUnitMeasureEstimatesQuery->orderBy('id');
            }
            $result['listUnitMeasureEstimates'] = $listUnitMeasureEstimatesQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => (string)$item->id,
                    'text' => $item->satuan,
                    'satuan' => $item->satuan,
                ];
            })->toArray();

            return $result; // ✅ HANYA DATA ARRAY, BUKAN RESPONSE
        });

        // Baru wrap dengan response di luar cache
        return response()->json($data)
            ->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
