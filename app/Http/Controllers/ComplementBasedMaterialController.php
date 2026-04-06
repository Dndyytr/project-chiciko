<?php

namespace App\Http\Controllers;

use App\Models\ComplementBasedMaterial;
use Illuminate\Http\Request;
use App\Models\IncomingComplementMaterial;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class ComplementBasedMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $complementBasedMaterials = ComplementBasedMaterial::when($search, function ($complementBasedMaterials) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $complementBasedMaterials = $complementBasedMaterials->where('nama_barang_sesuai_nota', 'like', '%' . request()->search . '%')
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

        return view('admin.complement-based-materials.index', compact('complementBasedMaterials'))
            ->with('i', ($page - 1) * $entries); // mengirim $complementBasedMaterials ke view complement-based-materials.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.complement-based-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'complement_materials_id' => 'required',
                'nama_barang_sesuai_nota' => 'required|string|max:255',
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

            $complementBasedMaterial = new ComplementBasedMaterial([
                'complement_materials_id' => $request->complement_materials_id,
                'nama_barang_sesuai_nota' => $request->nama_barang_sesuai_nota,
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

            $complementBasedMaterial->save();

            return redirect()->route('complement-based-materials.index')
                ->with('success', 'complement Based Material ' . $complementBasedMaterial->nama_barang_sesuai_nota . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('complement-based-materials.create')
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ComplementBasedMaterial $complementBasedMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ComplementBasedMaterial $complementBasedMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ComplementBasedMaterial $complementBasedMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ComplementBasedMaterial $complementBasedMaterial)
    {
        if ($complementBasedMaterial) {
            $complementBasedMaterial->delete();
            return redirect()->route('complement-based-materials.index')
                ->with('success', 'complement Based Material ' . $complementBasedMaterial->nama_barang_sesuai_nota . ' deleted successfully.');
        } else {
            return redirect()->route('complement-based-materials.index')->with('error', 'complement Based Material not found.');
        }
    }

    public function getMaterials(Request $request)
    {
        // Pastikan user login dan punya hak akses
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'q' => 'nullable|string|min:2|max:50'
        ]);

        $search = $request->query('q', '');
        $hasSearch = $search && strlen($search) >= 2;

        // Sanitize input
        $search = preg_replace('/[^a-zA-Z0-9\s]/', '', $search);

        // Buat cache key unik
        $cacheKey = "complement_materials_" . ($hasSearch ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search, $hasSearch) {
            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // data complement materials
            $complementMaterialsQuery = IncomingComplementMaterial::select([
                'id',
                'nama_barang_sesuai_nota',
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

            if ($hasSearch) {
                $complementMaterialsQuery->where(function ($q) use ($search) {
                    $q->where('nama_barang_sesuai_nota', 'like', "%{$search}%")
                        ->orWhere('satuan_ukur_sus', 'like', "%{$search}%")
                        ->orWhere('satuan_ukur_ksu', 'like', "%{$search}%")
                        ->orWhere('satuan_ukur_si', 'like', "%{$search}%")
                        ->orWhere('sub_total', 'like', "%{$search}%")
                        ->orWhere('total_nilai_si', 'like', "%{$search}%")
                        ->orWhere('harga_satuan_sus', 'like', "%{$search}%")
                        ->orWhere('harga_satuan_ukur_si', 'like', "%{$search}%");
                });
            } else {
                $complementMaterialsQuery->orderBy('nama_barang_sesuai_nota');
            }

            return $complementMaterialsQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => $item->id,
                    'text' => $item->nama_barang_sesuai_nota . ' | ' . $item->satuan_ukur_sus . ' | ' . $item->sub_total,
                    'nama_barang_sesuai_nota' => $item->nama_barang_sesuai_nota,
                    'jumlah_sus' => $item->jumlah_sus,
                    'satuan_ukur_sus' => $item->satuan_ukur_sus,
                    'harga_satuan_sus' => $item->harga_satuan_sus,
                    'jumlah_ksu' => $item->jumlah_ksu,
                    'satuan_ukur_ksu' => $item->satuan_ukur_ksu,
                    'total_nilai_si' => $item->total_nilai_si,
                    'satuan_ukur_si' => $item->satuan_ukur_si,
                    'harga_satuan_ukur_si' => $item->harga_satuan_ukur_si,
                    'sub_total' => $item->sub_total,
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
