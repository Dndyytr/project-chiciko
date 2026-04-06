<?php

namespace App\Http\Controllers;

use App\Models\CategoryExpense;
use App\Models\DataExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use DateTimeInterface;
use App\Services\CacheManagementService;

class DataExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $dataExpenses = DataExpense::when($search, function ($dataExpenses) {
            // untuk melakukan pencarian data jika $search ada nilainya
            $dataExpenses = $dataExpenses->where('tanggal_nota', 'like', '%' . request()->search . '%')
                ->orWhere('no_nota', 'like', '%' . request()->search . '%')
                ->orWhere('kategori', 'like', '%' . request()->search . '%')
                ->orWhere('keterangan', 'like', '%' . request()->search . '%')
                ->orWhere('harga_satuan', 'like', '%' . request()->search . '%')
                ->orWhere('kuantitas', 'like', '%' . request()->search . '%')
                ->orWhere('kredit', 'like', '%' . request()->search . '%'); // mencari nilai dari kolom nama
        })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.data-expenses.index', compact('dataExpenses'))
            ->with('i', ($page - 1) * $entries); // mengirim $dataExpenses ke view expense-categorys.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.data-expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal_nota' => 'required|date',
                'no_nota' => 'required|string|max:255',
                'kategori' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:255',
                'harga_satuan' => 'required|decimal:0,2',
                'kuantitas' => 'required|integer',
                'kredit' => 'required|decimal:0,2',
            ]);

            $dataExpense = new DataExpense([
                'tanggal_nota' => $request->tanggal_nota,
                'no_nota' => $request->no_nota,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'harga_satuan' => $request->harga_satuan,
                'kuantitas' => $request->kuantitas,
                'kredit' => $request->kredit,
            ]);

            $dataExpense->save();

            CacheManagementService::clearAppDataCache();
            return redirect()->route('data-expenses.index')->with('success', 'Data Expense ' . $dataExpense->no_nota . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('data-expenses.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DataExpense $dataExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataExpense $dataExpense)
    {
        return view('admin.data-expenses.edit', compact('dataExpense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataExpense $dataExpense)
    {
        try {
            $request->validate([
                'tanggal_nota' => 'required|date',
                'no_nota' => 'required|string|max:255',
                'kategori' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:255',
                'harga_satuan' => 'required|decimal:0,2',
                'kuantitas' => 'required|integer',
                'kredit' => 'required|decimal:0,2',
            ]);

            $input = $request->only([
                'tanggal_nota',
                'no_nota',
                'kategori',
                'keterangan',
                'harga_satuan',
                'kuantitas',
                'kredit',
            ]);

            $dataExpense->update($input);

            CacheManagementService::clearAppDataCache();
            return redirect()->route('data-expenses.index')->with('success', 'Data Expense ' . $dataExpense->no_nota . ' updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('data-expenses.edit', $dataExpense->id)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataExpense $dataExpense)
    {
        if ($dataExpense) {
            $dataExpense->delete();
            CacheManagementService::clearAppDataCache();
            return redirect()->route('data-expenses.index')->with('success', 'Data Expense ' . $dataExpense->no_nota . ' deleted successfully.');
        } else {
            return redirect()->route('data-expenses.index')->with('error', 'Data Expense not found.');
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
        $cacheKey = "data_expenses_" . ($search ? md5($search) : 'full');
        // menambahkan waktu cache untuk 10 menit
        $ttl = now()->addMinutes(10);

        // buat variabel data untuk menampung data dari cache atau query database
        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // Data Kategori
            $categoryExpenseQuery = CategoryExpense::select(['nama_kategori']);

            // cek jika ada isi dari search
            if ($hasSearch) {
                $categoryExpenseQuery->where('nama_kategori', 'like', "%{$search}%");
            } else {
                // jika tidak ada search, ambil 50 data pertama (A-Z)
                $categoryExpenseQuery->orderBy('nama_kategori');
            }

            // Ambil data kategori limit 50
            return $categoryExpenseQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => $item->nama_kategori,
                    'text' => $item->nama_kategori,
                ];
            })->toArray();
        });

        // Baru wrap dengan response di luar cache
        return response()->json($data)->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
