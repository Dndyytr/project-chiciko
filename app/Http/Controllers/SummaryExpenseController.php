<?php

namespace App\Http\Controllers;

use App\Models\SummaryExpense;
use App\Models\SummaryExpenseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use DateTimeInterface;
use App\Models\DataExpense;
use Illuminate\Support\Facades\DB;

class SummaryExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); // halaman saat ini, jika kosong halaman akan terisi 1
        $entries = request()->input('entries', 10); // Limit data yang ditampilkan, jika kosong akan terisi 10
        $search = request()->input('search'); // search value, jika kosong akan terisi null

        $summaryExpenses = SummaryExpense::with('summaryExpenseDetails')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('tanggal_mulai', 'like', "%{$search}%")
                        ->orWhere('tanggal_akhir', 'like', "%{$search}%")
                        ->orWhere('total_keseluruhan', 'like', "%{$search}%")
                        ->orWhereHas('summaryExpenseDetails', function ($q) use ($search) {
                            $q->where('kategori', 'like', "%{$search}%");
                        });
                });
            })->paginate($entries); // mengkonversi menjadi data dengan fungsi halaman

        return view('admin.summary-expenses.index', compact('summaryExpenses'))
            ->with('i', ($page - 1) * $entries); // mengirim $summaryExpenses ke view summary-expenses.index, dan i sebagai nomor urut
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.summary-expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal_mulai' => [
                    'required_without:tanggal_akhir',
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if (empty($value) && empty($request->tanggal_akhir)) {
                            $fail('Isi minimal salah satu tanggal (mulai atau akhir).');
                        }
                    },
                ],
                'tanggal_akhir' => [
                    'required_without:tanggal_mulai',
                    'nullable',
                    'date',
                    'after_or_equal:tanggal_mulai',
                    function ($attribute, $value, $fail) use ($request) {
                        if (empty($value) && empty($request->tanggal_mulai)) {
                            $fail('Isi minimal salah satu tanggal (mulai atau akhir).');
                        }
                    },
                ],
                'kategori' => 'required|array|min:1',
                'kategori.*' => 'required|string|max:255',
                'total_uang_keluar' => 'required|array|min:1',
                'total_uang_keluar.*' => 'required|decimal:0,2',
                'data_expenses_id' => 'required|array|min:1',
                'data_expenses_id.*' => 'required|exists:data_expenses,id',
                'total_keseluruhan' => 'required|decimal:0,2',
            ]);

            DB::beginTransaction();

            // Simpan summary expense
            $summaryExpense = new SummaryExpense([
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'total_keseluruhan' => $request->total_keseluruhan,
            ]);

            $summaryExpense->save();

            // Simpan detail summary expense
            foreach ($request->kategori as $index => $kategori) {
                SummaryExpenseDetail::create([
                    'summary_expenses_id' => $summaryExpense->id,
                    'data_expenses_id' => $request->data_expenses_id[$index],
                    'kategori' => $kategori,
                    'total_uang_keluar' => $request->total_uang_keluar[$index],
                    'urutan' => $index + 1,
                ]);
            }

            DB::commit();

            return redirect()->route('summary-expenses.index')->with('success', 'Summary Expense ' . $summaryExpense->tanggal_mulai . ' - ' . $summaryExpense->tanggal_akhir . ' created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('summary-expenses.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SummaryExpense $summaryExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SummaryExpense $summaryExpense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SummaryExpense $summaryExpense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SummaryExpense $summaryExpense)
    {
        if ($summaryExpense) {
            $summaryExpense->delete();
            return redirect()->route('summary-expenses.index')->with('success', 'Summary Expense ' . $summaryExpense->tanggal_mulai . ' - ' . $summaryExpense->tanggal_akhir . ' deleted successfully.');
        } else {
            return redirect()->route('summary-expenses.index')->with('error', 'Summary Expense not found.');
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
            'q' => 'nullable|string|min:2|max:50',
            'tanggal_mulai' => 'nullable|date_format:Y-m-d',
            'tanggal_akhir' => 'nullable|date_format:Y-m-d'
        ]);

        // Ambil parameter tanggal dari request
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        // Jika tidak ada tanggal satu pun yang diisi maka kosong
        if (!$tanggalMulai && !$tanggalAkhir) {
            return response()->json(['error' => 'Tanggal mulai atau tanggal akhir harus diisi'], 400);
        }

        // Validasi logika tanggal
        if ($tanggalMulai && $tanggalAkhir && strtotime($tanggalMulai) > strtotime($tanggalAkhir)) {
            return response()->json(['error' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'], 400);
        }

        // Cari hanya jika ada search query dari request dengan key 'q'
        $search = $request->query('q', '');

        // Sanitize input untuk mencegah SQL injection
        $search = preg_replace('/[^a-zA-Z0-9\s]/', '', $search);

        // Gunakan cache untuk performa maksimal
        $cacheKey = "data_expenses_" .
            ($tanggalMulai ? $tanggalMulai : 'all') . "_" .
            ($tanggalAkhir ? $tanggalAkhir : 'all') . "_" .
            ($search ? md5($search) : 'full');
        // menambahkan waktu cache untuk 10 menit
        $ttl = now()->addMinutes(10);

        // buat variabel data untuk menampung data dari cache atau query database
        $data = Cache::remember($cacheKey, $ttl, function () use ($search, $tanggalMulai, $tanggalAkhir) {
            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // Data Pengeluaran
            $dataExpenseQuery = DataExpense::select(['id', 'tanggal_nota', 'kategori', 'kredit']);

            // Tambahkan filter rentang tanggal jika ada
            if ($tanggalMulai && $tanggalAkhir) {
                // Filter tanggal_nota antara tanggal_mulai dan tanggal_akhir (inklusif)
                $dataExpenseQuery->whereDate('tanggal_nota', '>=', $tanggalMulai)
                    ->whereDate('tanggal_nota', '<=', $tanggalAkhir);
            } elseif ($tanggalMulai) {
                // Hanya filter tanggal mulai
                $dataExpenseQuery->whereDate('tanggal_nota', '>=', $tanggalMulai);
            } elseif ($tanggalAkhir) {
                // Hanya filter tanggal akhir
                $dataExpenseQuery->whereDate('tanggal_nota', '<=', $tanggalAkhir);
            }

            // cek jika ada isi dari search
            if ($hasSearch) {
                $dataExpenseQuery->where(function ($q) use ($search) {
                    $q->where('tanggal_nota', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('kredit', 'like', "%{$search}%");
                });
            } else {
                // jika tidak ada search, ambil 50 data pertama
                $dataExpenseQuery->orderBy('tanggal_nota', 'desc');
            }

            // Ambil data kategori limit 50
            return $dataExpenseQuery->limit(50)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tanggal_nota' => $item->tanggal_nota,
                    'kategori' => $item->kategori,
                    'kredit' => $item->kredit,
                ];
            })->toArray();
        });

        // Baru wrap dengan response di luar cache
        return response()->json($data)->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
