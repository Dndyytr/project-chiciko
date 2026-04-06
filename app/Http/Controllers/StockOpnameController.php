<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use Illuminate\Http\Request;
use App\Models\DataWarehouse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use App\Models\IncomingRawMaterial;
use App\Models\IncomingComplementMaterial;
use App\Services\CacheManagementService;


class StockOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $stockOpnames = StockOpname::when($search, function ($stockOpnames) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $stockOpnames = $stockOpnames->where('nama_gudang', 'like', '%' . request()->search . '%')
                ->orWhere('kode_item', 'like', '%' . request()->search . '%')
                ->orWhere('kode_barcode', 'like', '%' . request()->search . '%')
                ->orWhere('nama_item', 'like', '%' . request()->search . '%')
                ->orWhere('satuan', 'like', '%' . request()->search . '%')
                ->orWhere('buku', 'like', '%' . request()->search . '%')
                ->orWhere('fisik', 'like', '%' . request()->search . '%')
                ->orWhere('selisih', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode_bahan_baku
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.stock-opnames.index', compact('stockOpnames'))
            ->with('i', ($page - 1) * $entries); // mengirim $stockOpnames ke view data-warehouses.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.stock-opnames.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'data_warehouses_id' => 'required',
                'nama_gudang' => 'required|string|max:255',
                'kode_item' => 'required|string|max:255',
                'kode_barcode' => 'required|string|max:255',
                'nama_item' => 'required|string|max:255',
                'satuan' => 'required|string|max:255',
                'buku' => 'required|integer|min:0',
                'fisik' => 'required|integer|min:0',
                'selisih' => 'required|integer',
                'material_type' => 'required|string|max:255',
                'material_id' => 'required',
            ]);

            $stockOpname = new StockOpname([
                'data_warehouses_id' => $request->data_warehouses_id,
                'nama_gudang' => $request->nama_gudang,
                'kode_item' => $request->kode_item,
                'kode_barcode' => $request->kode_barcode,
                'nama_item' => $request->nama_item,
                'satuan' => $request->satuan,
                'buku' => $request->buku,
                'fisik' => $request->fisik,
                'selisih' => $request->selisih,
                'material_type' => $request->material_type,
                'material_id' => $request->material_id,
            ]);
            $stockOpname->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('stock-opnames.index')
                ->with('success', 'Stock Opname ' . $stockOpname->nama_item . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('stock-opnames.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockOpname $stockOpname)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockOpname $stockOpname)
    {
        return view('admin.stock-opnames.edit', compact('stockOpname'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockOpname $stockOpname)
    {
        try {
            $request->validate([
                'data_warehouses_id' => 'required',
                'nama_gudang' => 'required|string|max:255',
                'kode_item' => 'required|string|max:255',
                'kode_barcode' => 'required|string|max:255',
                'nama_item' => 'required|string|max:255',
                'satuan' => 'required|string|max:255',
                'buku' => 'required|integer|min:0',
                'fisik' => 'required|integer|min:0',
                'selisih' => 'required|integer|min:0',
                'material_type' => 'required|string|max:255',
                'material_id' => 'required',
            ]);

            $input = $request->only([
                'data_warehouses_id',
                'nama_gudang',
                'kode_item',
                'kode_barcode',
                'nama_item',
                'satuan',
                'buku',
                'fisik',
                'selisih',
                'material_type',
                'material_id',
            ]);

            $stockOpname->update($input);

            return redirect()->route('stock-opnames.index')
                ->with('success', 'Stock Opname ' . $stockOpname->nama_item . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('stock-opnames.edit', $stockOpname->id)
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockOpname $stockOpname)
    {
        if ($stockOpname) {
            $stockOpname->delete();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname ' . $stockOpname->nama_item . ' deleted successfully.');
        } else {
            return redirect()->route('stock-opnames.index')->with('error', 'Stock Opname not found.');
        }
    }

    public function dataGudang(Request $request)
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
        $cacheKey = "data_gudang_" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // Data Gudang
            $warehouseQuery = DataWarehouse::select(['id', 'nama_gudang']);

            if ($hasSearch) {
                $warehouseQuery->where('nama_gudang', 'like', "%{$search}%");
            } else {
                $warehouseQuery->orderBy('id');
            }

            return $warehouseQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => $item->id,
                    'text' => $item->nama_gudang,
                    'nama_gudang' => $item->nama_gudang
                ];
            })->toArray();
        });

        // Baru wrap dengan response di luar cache
        return response()->json($data)->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }

    // Tambahkan method di StockOpnameController.php
    public function getMaterials(Request $request)
    {
        // Pastikan user login dan punya hak akses
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validasi input
        $request->validate([
            'jenis_bm' => 'required|string',
            'search' => 'nullable|string|min:2|max:50'
        ]);

        $jenisBm = $request->input('jenis_bm', '');
        $search = $request->input('search', '');

        // Sanitize input
        $search = preg_replace('/[^a-zA-Z0-9\s]/', '', $search);

        // Buat cache key
        $cacheKey = "materials_{$jenisBm}_" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $results = Cache::remember($cacheKey, $ttl, function () use ($jenisBm, $search) {
            $hasSearch = $search && strlen($search) >= 2;
            $results = [];

            if ($jenisBm === 'App\\Models\\IncomingRawMaterial') {
                $query = IncomingRawMaterial::select([
                    'id',
                    'kode_barcode',
                    'nama_barang_detail',
                    'qty_roll',
                    'satuan_ukur',
                ]);

                if ($hasSearch) {
                    $query->where(function ($q) use ($search) {
                        $q->where('kode_barcode', 'like', "%{$search}%")
                            ->orWhere('nama_barang_detail', 'like', "%{$search}%")
                            ->orWhere('qty_roll', 'like', "%{$search}%")
                            ->orWhere('satuan_ukur', 'like', "%{$search}%");
                    });
                } else {
                    $query->orderBy('nama_barang_detail');
                }

                $results = $query->limit(50)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kode_barcode' => $item->kode_barcode,
                        'nama_barang_detail' => $item->nama_barang_detail,
                        'qty_roll' => $item->qty_roll,
                        'satuan_ukur' => $item->satuan_ukur,
                    ];
                })->toArray();

            } elseif ($jenisBm === 'App\\Models\\IncomingComplementMaterial') {
                $query = IncomingComplementMaterial::select([
                    'id',
                    'kode',
                    'nama_barang_sesuai_nota',
                    'jumlah_sus',
                    'jenis',
                    'satuan_ukur_sus',
                ]);

                if ($hasSearch) {
                    $query->where(function ($q) use ($search) {
                        $q->where('kode', 'like', "%{$search}%")
                            ->orWhere('nama_barang_sesuai_nota', 'like', "%{$search}%")
                            ->orWhere('jumlah_sus', 'like', "%{$search}%")
                            ->orWhere('jenis', 'like', "%{$search}%")
                            ->orWhere('satuan_ukur_sus', 'like', "%{$search}%");
                    });
                } else {
                    $query->orderBy('nama_barang_sesuai_nota');
                }

                $results = $query->limit(50)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kode' => $item->kode,
                        'nama_barang_sesuai_nota' => $item->nama_barang_sesuai_nota,
                        'jumlah_sus' => $item->jumlah_sus,
                        'jenis' => $item->jenis,
                        'satuan_ukur_sus' => $item->satuan_ukur_sus,
                    ];
                })->toArray();
            }
            return $results;

        });
        return response()->json($results)
            ->header('Cache-Control', 'public, max-age=60')
            ->header('ETag', md5(json_encode($results)))
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
