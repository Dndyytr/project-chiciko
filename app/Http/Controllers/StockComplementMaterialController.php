<?php

namespace App\Http\Controllers;

use App\Models\StockComplementMaterial;
use Illuminate\Http\Request;
use App\Models\IncomingComplementMaterial;
use App\Models\StockOpname;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;

class StockComplementMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $stockComplementMaterials = StockComplementMaterial::when($search, function ($stockComplementMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $stockComplementMaterials = $stockComplementMaterials->where('barang_keluar', 'like', '%' . request()->search . '%')
                ->orWhere('harga_barang_keluar', 'like', '%' . request()->search . '%')
                ->orWhere('stock_akhir', 'like', '%' . request()->search . '%')
                ->orWhere('total_harga_stock_akhir', 'like', '%' . request()->search . '%')
                ->orWhere('harga_satuan_stock_akhir', 'like', '%' . request()->search . '%')
                ->orWhere('nama_item', 'like', '%' . request()->search . '%');
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.stock-complement-materials.index', compact('stockComplementMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $stockComplementMaterials ke view stock-complement-materials.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stock-complement-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'incoming_complement_materials_id' => 'required',
                'nama_item' => 'required|string',
                'barang_keluar' => 'required|integer',
                'stock_opnames_id' => 'nullable',
                'harga_barang_keluar' => 'required|decimal:0,2',
                'stock_akhir' => 'required|integer',
                'total_harga_stock_akhir' => 'required|decimal:0,2',
                'harga_satuan_stock_akhir' => 'required|decimal:0,2',
            ]);

            $stockComplementMaterial = new StockComplementMaterial([
                'incoming_complement_materials_id' => $request->incoming_complement_materials_id,
                'stock_opnames_id' => $request->stock_opnames_id,
                'nama_item' => $request->nama_item,
                'barang_keluar' => $request->barang_keluar,
                'harga_barang_keluar' => $request->harga_barang_keluar,
                'stock_akhir' => $request->stock_akhir,
                'total_harga_stock_akhir' => $request->total_harga_stock_akhir,
                'harga_satuan_stock_akhir' => $request->harga_satuan_stock_akhir,
            ]);

            $stockComplementMaterial->save();

            return redirect()->route('stock-complement-materials.index')->with('success', 'Stock Complement Material ' . $stockComplementMaterial->nama_item . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('stock-complement-materials.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockComplementMaterial $stockComplementMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockComplementMaterial $stockComplementMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockComplementMaterial $stockComplementMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockComplementMaterial $stockComplementMaterial)
    {
        if ($stockComplementMaterial) {
            $stockComplementMaterial->delete();
            return redirect()->route('stock-complement-materials.index')->with('success', 'Stock Complement Material deleted successfully.');
        } else {
            return back()->with('error', 'Stock Complement Material not found!');
        }
    }

    // API
    public function getAllData(Request $request)
    {
        // Pastikan user login dan punya hak akses
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validasi input
        $request->validate([
            'q' => 'nullable|string|min:2|max:50'
        ]);

        // Cari hanya jika ada search query dari request dengan key 'q'
        $search = $request->query('q', '');

        // Sanitize input untuk mencegah SQL injection
        $search = preg_replace('/[^a-zA-Z0-9\s]/', '', $search);

        // Gunakan cache untuk performa maksimal
        $cacheKey = "stock_complement_materials_" . ($search ? md5($search) : 'full');
        // menambahkan waktu cache untuk 10 menit
        $ttl = now()->addMinutes(10);

        // buat variabel data untuk menampung data dari cache atau query database
        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            // cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // data incoming complement materials
            $incomingComplementMaterialsQuery = IncomingComplementMaterial::select([
                'id',
                'nama_barang_sesuai_nota',
                'harga_satuan_ukur_si',
                'total_nilai_si',
            ]);

            // cek jika ada isi dari search
            if ($hasSearch) {
                $incomingComplementMaterialsQuery->where(function ($q) use ($search) {
                    $q->where('harga_satuan_ukur_si', 'like', "%{$search}%")
                        ->orWhere('nama_barang_sesuai_nota', 'like', "%{$search}%")
                        ->orWhere('total_nilai_si', 'like', "%{$search}%");
                });
            } else {
                // jika tidak ada search, ambil 50 data pertama (A-Z)
                $incomingComplementMaterialsQuery->orderBy('id');
            }

            // Ambil data incoming complement materials
            $incomingComplementMaterials = $incomingComplementMaterialsQuery->limit(50)->get();

            // Ambil ID material untuk query stock opnames
            $materialIds = $incomingComplementMaterials->pluck('id')->toArray();

            // Alternatif untuk semua versi MySQL
            // Menggunakan subquery untuk mendapatkan created_at terbaru per material_id
            // Kompatibel dengan MySQL < 8.0 yang tidak mendukung window function + HAVING
            if (!empty($materialIds)) {
                $latestStockOpnames = StockOpname::whereIn('material_id', $materialIds)
                    ->where('material_type', 'App\\Models\\IncomingComplementMaterial')
                    ->whereIn('created_at', function ($query) use ($materialIds) {
                        // Subquery untuk mendapatkan created_at terbaru untuk setiap material_id
                        $query->from('stock_opnames')
                            ->select(DB::raw('MAX(created_at)')) // Ambil tanggal terbaru
                            ->where('material_type', 'App\\Models\\IncomingComplementMaterial') // Filter tipe material
                            ->whereIn('material_id', $materialIds) // Hanya material yang relevan
                            ->groupBy('material_id'); // Kelompokkan berdasarkan material_id
                    })
                    ->get();

                // Konversi ke map dengan material_id sebagai key untuk akses cepat
                // Misalnya: $stockOpnamesMap[1] akan mengembalikan stock opname untuk material ID 1
                $stockOpnamesMap = $latestStockOpnames->keyBy('material_id');
            } else {
                // Jika tidak ada material ID, buat collection kosong
                $stockOpnamesMap = collect();
            }

            // hasil dari pengambilan atau pencarian data incoming complement materials
            $result = $incomingComplementMaterials->map(function ($item) use ($stockOpnamesMap) {
                // Cari stock opname terbaru untuk material ini dari map
                $latestStockOpname = $stockOpnamesMap->get($item->id);

                // Tentukan total_nilai_si:
                // - Jika ada stock opname dengan material_type yang benar, gunakan fisik dari stock opname
                // - Jika tidak ada, gunakan total_nilai_si dari incoming complement material
                $totalNilaiSI = $latestStockOpname ? $latestStockOpname->fisik : $item->total_nilai_si;

                return [
                    'value' => $item->id,
                    'text' => "{$item->nama_barang_sesuai_nota} | {$item->harga_satuan_ukur_si} | {$totalNilaiSI}",
                    'nama_barang_sesuai_nota' => $item->nama_barang_sesuai_nota,
                    'harga_satuan_ukur_si' => $item->harga_satuan_ukur_si,
                    'total_nilai_si' => $totalNilaiSI, // Sudah dihitung berdasarkan logika di atas
                    'has_stock_opnames' => (bool) $latestStockOpname, // Informasi tambahan jika diperlukan
                    'stock_opnames_id' => $latestStockOpname ? $latestStockOpname->id : null, // ID stock opname jika ada
                ];
            })->toArray();

            return $result; // ✅ HANYA DATA ARRAY, BUKAN RESPONSE
        });

        return response()->json($data)
            ->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
