<?php

namespace App\Http\Controllers;

use App\Models\PurchaseBasedNote;
use Illuminate\Http\Request;
use App\Models\IncomingRawMaterial;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class PurchaseBasedNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $purchaseBasedNotes = PurchaseBasedNote::when($search, function ($purchaseBasedNotes) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $purchaseBasedNotes = $purchaseBasedNotes->where('tanggal_nota', 'like', '%' . request()->search . '%')
                ->orWhere('no_kwitansi', 'like', '%' . request()->search . '%')
                ->orWhere('kode_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('nama_supplier', 'like', '%' . request()->search . '%')
                ->orWhere('qty_roll', 'like', '%' . request()->search . '%')
                ->orWhere('qty_yard', 'like', '%' . request()->search . '%')
                ->orWhere('jumlah', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom kode
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.purchase-based-notes.index', compact('purchaseBasedNotes'))
            ->with('i', ($page - 1) * $entries); // mengirim $purchaseBasedNotes ke view list-accounting-estimates.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.purchase-based-notes.create');
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
                'qty_roll' => 'required|integer|min:0',
                'qty_yard' => 'required|integer|min:0',
                'jumlah' => 'required|decimal:0,2|min:0',
            ]);

            $purchaseBasedNote = new PurchaseBasedNote([
                'tanggal_nota' => $request->tanggal_nota,
                'no_kwitansi' => $request->no_kwitansi,
                'kode_supplier' => $request->kode_supplier,
                'nama_supplier' => $request->nama_supplier,
                'qty_roll' => $request->qty_roll,
                'qty_yard' => $request->qty_yard,
                'jumlah' => $request->jumlah,
            ]);

            $purchaseBasedNote->save();

            return redirect()->route('purchase-based-notes.index')
                ->with('success', 'Purchase Based Note ' . $purchaseBasedNote->no_kwitansi . ' created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('purchase-based-notes.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseBasedNote $purchaseBasedNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseBasedNote $purchaseBasedNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseBasedNote $purchaseBasedNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseBasedNote $purchaseBasedNote)
    {
        if ($purchaseBasedNote) {
            $purchaseBasedNote->delete();
            return redirect()->route('purchase-based-notes.index')->with('success', 'Purchase Based Note ' . $purchaseBasedNote->no_kwitansi . ' deleted successfully.');
        } else {
            return redirect()->route('purchase-based-notes.index')->with('error', 'Purchase Based Note not found.');
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
        $cacheKey = "purchase_notes_" . ($hasSearch ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search, $hasSearch) {
            $query = IncomingRawMaterial::selectRaw('
                    CONCAT(tanggal_nota, " | ", no_kwitansi, " | ", nama_supplier) as text,
                    CONCAT(tanggal_nota, " | ", no_kwitansi, " | ", nama_supplier) as value,
                    tanggal_nota,
                    no_kwitansi,
                    kode_supplier,
                    nama_supplier,
                    SUM(qty_roll) as qty_roll,
                    SUM(yard) as qty_yard,
                    SUM(total_harga) as jumlah
                ')
                ->groupBy('tanggal_nota', 'no_kwitansi', 'kode_supplier', 'nama_supplier');

            if ($hasSearch) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_kwitansi', 'like', "%{$search}%")
                        ->orWhere('nama_supplier', 'like', "%{$search}%")
                        ->orWhere('kode_supplier', 'like', "%{$search}%");
                });
            } else {
                // Jika tidak ada search, ambil 50 data terbaru
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
                    'qty_roll' => $item->qty_roll,
                    'qty_yard' => $item->qty_yard,
                    'jumlah' => $item->jumlah
                ];
            })->toArray();
        });

        return response()->json($data)
            ->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data)))
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
