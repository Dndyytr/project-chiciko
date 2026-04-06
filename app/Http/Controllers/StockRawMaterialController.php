<?php

namespace App\Http\Controllers;

use App\Models\StockRawMaterial;
use Illuminate\Http\Request;
use App\Models\IncomingRawMaterial;
use App\Models\StockOpname;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;

class StockRawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $stockRawMaterials = StockRawMaterial::when($search, function ($stockRawMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $stockRawMaterials = $stockRawMaterials->where('keluar_roll', 'like', '%' . request()->search . '%')
                ->orWhere('keluar_yard', 'like', '%' . request()->search . '%')
                ->orWhere('stock_akhir', 'like', '%' . request()->search . '%')
                ->orWhere('sisa_roll', 'like', '%' . request()->search . '%')
                ->orWhere('sisa_yard', 'like', '%' . request()->search . '%')
                ->orWhere('total_harga', 'like', '%' . request()->search . '%')
                ->orWhere('harga_per_satuan', 'like', '%' . request()->search . '%')
                ->orWhere('nama_item', 'like', '%' . request()->search . '%');
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.stock-raw-materials.index', compact('stockRawMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $stockRawMaterials ke view stock-raw-materials.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stock-raw-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'incoming_raw_materials_id' => 'required',
                'nama_item' => 'required|string|max:255',
                'stock_opnames_id' => 'nullable',
                'keluar_roll' => 'required|numeric',
                'keluar_yard' => 'required|integer',
                'stock_akhir' => 'required|integer',
                'sisa_roll' => 'required|numeric',
                'sisa_yard' => 'required|decimal:0,2',
                'total_harga' => 'required|decimal:0,2',
                'harga_per_satuan' => 'required|decimal:0,2',
            ]);

            $stockRawMaterial = new StockRawMaterial([
                'incoming_raw_materials_id' => $request->incoming_raw_materials_id,
                'nama_item' => $request->nama_item,
                'stock_opnames_id' => $request->stock_opnames_id,
                'keluar_roll' => $request->keluar_roll,
                'keluar_yard' => $request->keluar_yard,
                'stock_akhir' => $request->stock_akhir,
                'sisa_roll' => $request->sisa_roll,
                'sisa_yard' => $request->sisa_yard,
                'total_harga' => $request->total_harga,
                'harga_per_satuan' => $request->harga_per_satuan,
            ]);

            $stockRawMaterial->save();

            return redirect()->route('stock-raw-materials.index')->with('success', 'Stock Raw Material created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('stock-raw-materials.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockRawMaterial $stockRawMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockRawMaterial $stockRawMaterial)
    {
        // cek apakah ada data stock opnames di database, jika ada variabel $stockOpnames akan bernilai true, jika tidak bernilai false
        $stockOpnames = StockOpname::exists();
        return view('admin.stock-raw-materials.edit', compact('stockRawMaterial', 'stockOpnames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockRawMaterial $stockRawMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockRawMaterial $stockRawMaterial)
    {
        if ($stockRawMaterial) {
            $stockRawMaterial->delete();
            return redirect()->route('stock-raw-materials.index')->with('success', 'Stock Raw Material deleted successfully.');
        } else {
            return back()->with('error', 'Stock Raw Material not found!');
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
        $cacheKey = "stock_raw_materials_" . ($search ? md5($search) : 'full');
        // menambahkan waktu cache untuk 10 menit
        $ttl = now()->addMinutes(10);

        // buat variabel data untuk menampung data dari cache atau query database
        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            // cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // data incoming raw materials
            $incomingRawMaterialsQuery = IncomingRawMaterial::select([
                'id',
                'nama_barang_detail',
                'yard',
                'qty_roll',
                'jumlah_roll_satuan',
                'harga_per_satuan',
            ]);

            // cek jika ada isi dari search
            if ($hasSearch) {
                $incomingRawMaterialsQuery->where(function ($q) use ($search) {
                    $q->where('yard', 'like', "%{$search}%")
                        ->orWhere('nama_barang_detail', 'like', "%{$search}%")
                        ->orWhere('qty_roll', 'like', "%{$search}%")
                        ->orWhere('jumlah_roll_satuan', 'like', "%{$search}%")
                        ->orWhere('harga_per_satuan', 'like', "%{$search}%");
                });
            } else {
                // jika tidak ada search, ambil 50 data pertama (A-Z)
                $incomingRawMaterialsQuery->orderBy('id');
            }

            // Ambil data incoming raw materials
            $incomingRawMaterials = $incomingRawMaterialsQuery->limit(50)->get();

            // Ambil ID material untuk query stock opnames
            $materialIds = $incomingRawMaterials->pluck('id')->toArray();

            // Alternatif untuk semua versi MySQL
            // Menggunakan subquery untuk mendapatkan created_at terbaru per material_id
            // Kompatibel dengan MySQL < 8.0 yang tidak mendukung window function + HAVING
            if (!empty($materialIds)) {
                $latestStockOpnames = StockOpname::whereIn('material_id', $materialIds)
                    ->where('material_type', 'App\\Models\\IncomingRawMaterial')
                    ->whereIn('created_at', function ($query) use ($materialIds) {
                        // Subquery untuk mendapatkan created_at terbaru untuk setiap material_id
                        $query->from('stock_opnames')
                            ->select(DB::raw('MAX(created_at)')) // Ambil tanggal terbaru
                            ->where('material_type', 'App\\Models\\IncomingRawMaterial') // Filter tipe material
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

            // hasil dari pengambilan atau pencarian data incoming raw materials
            $result = $incomingRawMaterials->map(function ($item) use ($stockOpnamesMap) {
                // Cari stock opname terbaru untuk material ini dari map
                $latestStockOpname = $stockOpnamesMap->get($item->id);

                // Tentukan qty_roll:
                // - Jika ada stock opname dengan material_type yang benar, gunakan fisik dari stock opname
                // - Jika tidak ada, gunakan qty_roll dari incoming raw material
                $qtyRoll = $latestStockOpname ? $latestStockOpname->fisik : $item->qty_roll;

                return [
                    'value' => $item->id,
                    'text' => "{$item->nama_barang_detail} | {$item->yard} | {$qtyRoll}",
                    'nama_barang_detail' => $item->nama_barang_detail,
                    'yard' => $item->yard,
                    'qty_roll' => $qtyRoll, // Sudah dihitung berdasarkan logika di atas
                    'jumlah_roll_satuan' => $item->jumlah_roll_satuan,
                    'harga_per_satuan' => $item->harga_per_satuan,
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
