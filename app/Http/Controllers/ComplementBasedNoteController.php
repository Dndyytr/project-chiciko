<?php

namespace App\Http\Controllers;

use App\Models\ComplementBasedNote;
use Illuminate\Http\Request;
use App\Models\IncomingComplementMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class ComplementBasedNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $complementBasedNotes = ComplementBasedNote::when($search, function ($complementBasedNotes) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $complementBasedNotes = $complementBasedNotes->where('tanggal_nota', 'like', '%' . request()->search . '%')
                ->orWhere('no_kwitansi', 'like', '%' . request()->search . '%')
                ->orWhere('kode_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('nama_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('total_harga', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.complement-based-notes.index', compact('complementBasedNotes'))
            ->with('i', ($page - 1) * $entries); // mengirim $complementBasedNotes ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.complement-based-notes.create');
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
                'total_harga' => 'required|decimal:0,2|min:0',
            ]);

            $complementBasedNote = new ComplementBasedNote([
                'tanggal_nota' => $request->tanggal_nota,
                'no_kwitansi' => $request->no_kwitansi,
                'kode_supplier' => $request->kode_supplier,
                'nama_supplier' => $request->nama_supplier,
                'total_harga' => $request->total_harga,
            ]);

            $complementBasedNote->save();

            return redirect()->route('complement-based-notes.index')
                ->with('success', 'complement Based Note ' . $complementBasedNote->no_kwitansi . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('complement-based-notes.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ComplementBasedNote $complementBasedNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ComplementBasedNote $complementBasedNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ComplementBasedNote $complementBasedNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ComplementBasedNote $complementBasedNote)
    {
        if ($complementBasedNote) {
            $complementBasedNote->delete();
            return redirect()->route('complement-based-notes.index')
                ->with('success', 'complement Based Note ' . $complementBasedNote->no_kwitansi . ' deleted successfully.');
        } else {
            return redirect()->route('complement-based-notes.index')->with('error', 'complement Based Note not found.');
        }
    }

    public function getNotes(Request $request)
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
        $cacheKey = "complement_notes_" . ($hasSearch ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search, $hasSearch) {
            $query = IncomingComplementMaterial::selectRaw('
                CONCAT(tanggal_nota, " | ", no_kwitansi, " | ", nama_supplier) as text,
                CONCAT(tanggal_nota, " | ", no_kwitansi, " | ", nama_supplier) as value,
                tanggal_nota,
                no_kwitansi,
                kode_supplier,
                nama_supplier,
                SUM(sub_total) as total_harga
            ')
                ->groupBy('tanggal_nota', 'no_kwitansi', 'kode_supplier', 'nama_supplier');

            if ($hasSearch) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_kwitansi', 'like', "%{$search}%")
                        ->orWhere('nama_supplier', 'like', "%{$search}%")
                        ->orWhere('kode_supplier', 'like', "%{$search}%");
                });
            } else {
                $query->orderBy('tanggal_nota', 'desc')
                    ->orderBy('no_kwitansi');
            }

            $results = $query->limit(50)->get();

            return $results->map(function ($item) {
                return [
                    'value' => $item->value,
                    'text' => $item->text,
                    'tanggal_nota' => $item->tanggal_nota,
                    'no_kwitansi' => $item->no_kwitansi,
                    'kode_supplier' => $item->kode_supplier,
                    'nama_supplier' => $item->nama_supplier,
                    'total_harga' => $item->total_harga
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
