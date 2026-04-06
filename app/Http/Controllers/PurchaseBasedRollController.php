<?php

namespace App\Http\Controllers;

use App\Models\IncomingRawMaterial;
use App\Models\PurchaseBasedRoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;

class PurchaseBasedRollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $purchaseBasedRolls = PurchaseBasedRoll::when($search, function ($purchaseBasedRolls) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $purchaseBasedRolls = $purchaseBasedRolls->where('kode_barcode', 'like', '%' . request()->search . '%')
                ->orWhere('nama_barang', 'like', '%' . request()->search . '%')
                ->orWhere('jenis_kain', 'like', '%' . request()->search . '%')
                ->orWhere('warna', 'like', '%' . request()->search . '%')
                ->orWhere('yard_per_roll', 'like', '%' . request()->search . '%')
                ->orWhere('qty_roll', 'like', '%' . request()->search . '%')
                ->orWhere('kg_per_roll', 'like', '%' . request()->search . '%')
                ->orWhere('jumlah_roll_satuan', 'like', '%' . request()->search . '%')
                ->orWhere('total_harga', 'like', '%' . request()->search . '%')
                ->orWhere('harga_per_satuan', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.purchase-based-rolls.index', compact('purchaseBasedRolls'))
            ->with('i', ($page - 1) * $entries); // mengirim $purchaseBasedRolls ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.purchase-based-rolls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'incoming_raw_materials_id' => 'required|exists:incoming_raw_materials,id',
                'kode_barcode' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
                'jenis_kain' => 'required|string|max:255',
                'warna' => 'required|string|max:255',
                'qty_roll' => 'required|integer|min:0',
                'yard_per_roll' => 'required|decimal:0,2|min:0',
                'kg_per_roll' => 'required|decimal:0,2|min:0',
                'jumlah_roll_satuan' => 'required|integer|min:0',
                'total_harga' => 'required|decimal:0,2|min:0',
                'harga_per_satuan' => 'required|decimal:0,2|min:0',
            ]);

            $purchaseBasedRoll = new PurchaseBasedRoll([
                'incoming_raw_materials_id' => $request->incoming_raw_materials_id,
                'kode_barcode' => $request->kode_barcode,
                'nama_barang' => $request->nama_barang,
                'jenis_kain' => $request->jenis_kain,
                'warna' => $request->warna,
                'qty_roll' => $request->qty_roll,
                'yard_per_roll' => $request->yard_per_roll,
                'kg_per_roll' => $request->kg_per_roll,
                'jumlah_roll_satuan' => $request->jumlah_roll_satuan,
                'total_harga' => $request->total_harga,
                'harga_per_satuan' => $request->harga_per_satuan,
            ]);
            $purchaseBasedRoll->save();
            return redirect()->route('purchase-based-rolls.index')->with('success', 'Purchase Based Roll ' . $purchaseBasedRoll->kode_barcode . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('purchase-based-rolls.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseBasedRoll $purchaseBasedRoll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseBasedRoll $purchaseBasedRoll)
    {
        return view('admin.purchase-based-rolls.edit', compact('purchaseBasedRoll'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseBasedRoll $purchaseBasedRoll)
    {
        try {
            $request->validate([
                'incoming_raw_materials_id' => 'required|exists:incoming_raw_materials,id',
                'kode_barcode' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
                'jenis_kain' => 'required|string|max:255',
                'warna' => 'required|string|max:255',
                'qty_roll' => 'required|integer|min:0',
                'yard_per_roll' => 'required|decimal:0,2|min:0',
                'kg_per_roll' => 'required|decimal:0,2|min:0',
                'jumlah_roll_satuan' => 'required|integer|min:0',
                'total_harga' => 'required|decimal:0,2|min:0',
                'harga_per_satuan' => 'required|decimal:0,2|min:0',
            ]);

            $input = $request->only([
                'incoming_raw_materials_id',
                'kode_barcode',
                'nama_barang',
                'jenis_kain',
                'warna',
                'qty_roll',
                'yard_per_roll',
                'kg_per_roll',
                'jumlah_roll_satuan',
                'total_harga',
                'harga_per_satuan',
            ]);
            $purchaseBasedRoll->update($input);
            return redirect()->route('purchase-based-rolls.index')->with('success', 'Purchase Based Roll ' . $purchaseBasedRoll->kode_barcode . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('purchase-based-rolls.edit', $purchaseBasedRoll->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseBasedRoll $purchaseBasedRoll)
    {
        if ($purchaseBasedRoll) {
            $purchaseBasedRoll->delete();
            return redirect()->route('purchase-based-rolls.index')->with('success', 'Purchase Based Roll ' . $purchaseBasedRoll->kode_barcode . ' deleted successfully.');
        } else {
            return back()->with('error', 'Purchase Based Roll not found!');
        }
    }

    public function getRolls(Request $request)
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
        $cacheKey = "data_rolls_" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            $rawMaterialsQuery = IncomingRawMaterial::select([
                'id',
                'kode_barcode',
                'nama_barang',
                'jenis_kain',
                'warna',
                'jumlah_roll_satuan',
                'total_harga',
                'qty_roll',
            ]);

            if ($hasSearch) {
                $rawMaterialsQuery->where(function ($q) use ($search) {
                    $q->where('kode_barcode', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('jenis_kain', 'like', "%{$search}%")
                        ->orWhere('warna', 'like', "%{$search}%");
                });
            } else {
                $rawMaterialsQuery->orderBy('kode_barcode');
            }

            return $rawMaterialsQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => $item->id,
                    'text' => $item->kode_barcode . ' | ' . $item->nama_barang . ' | ' . $item->jenis_kain . ' | ' . $item->warna,
                    'id' => $item->id,
                    'kode_barcode' => $item->kode_barcode,
                    'nama_barang' => $item->nama_barang,
                    'jenis_kain' => $item->jenis_kain,
                    'warna' => $item->warna,
                    'jumlah_roll_satuan' => $item->jumlah_roll_satuan,
                    'total_harga' => $item->total_harga,
                    'qty_roll' => $item->qty_roll,
                ];
            })->toArray();
        });

        // Baru wrap dengan response di luar cache
        return response()->json($data)
            ->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
