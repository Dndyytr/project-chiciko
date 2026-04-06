<?php

namespace App\Http\Controllers;

use App\Models\DataTailor;
use Illuminate\Http\Request;
use App\Models\TailorCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;


class DataTailorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $entries = $request->input('entries', 10);
        $search = $request->input('search');

        // Filter parameters
        $namaKoordinator = $request->input('nama_koordinator');
        $namaDaerah = $request->input('nama_daerah');

        $dataTailors = DataTailor::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_koordinator', 'like', '%' . $search . '%')
                    ->orWhere('kode_koordinator', 'like', '%' . $search . '%')
                    ->orWhere('nama_daerah', 'like', '%' . $search . '%')
                    ->orWhere('nama_penjahit', 'like', '%' . $search . '%')
                    ->orWhere('kode_penjahit', 'like', '%' . $search . '%');
            });
        })
            ->when($namaKoordinator, function ($query) use ($namaKoordinator) {
                $query->where('nama_koordinator', $namaKoordinator);
            })
            ->when($namaDaerah, function ($query) use ($namaDaerah) {
                $query->where('nama_daerah', $namaDaerah);
            })
            ->paginate($entries);

        // Jika request AJAX, return JSON dengan HTML
        if ($request->ajax()) {
            $i = ($page - 1) * $entries;

            $html = '';
            if ($dataTailors->count() > 0) {
                foreach ($dataTailors as $dataTailor) {
                    $i++;
                    $html .= '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">';
                    $html .= '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">' . $i . '</td>';
                    $html .= '<td class="px-6 py-2 text-center">' . e($dataTailor->nama_koordinator) . '</td>';
                    $html .= '<td class="px-6 py-2 text-center">' . e($dataTailor->kode_koordinator) . '</td>';
                    $html .= '<td class="px-6 py-2 text-center">' . e($dataTailor->nama_daerah) . '</td>';
                    $html .= '<td class="px-6 py-2 text-center">' . e($dataTailor->nama_penjahit) . '</td>';
                    $html .= '<td class="px-6 py-2 text-center">' . e($dataTailor->kode_penjahit) . '</td>';
                    $html .= '<td class="px-6 py-2 text-center">' . format_tanggal_id($dataTailor->created_at, true, true) . '</td>';
                    $html .= '<td class="px-6 py-2 text-center">';
                    $html .= '<form action="' . route('data-tailors.destroy', $dataTailor->id) . '" method="POST">';
                    $html .= '<div class="flex">';
                    $html .= csrf_field();
                    $html .= method_field('DELETE');
                    $html .= '<button type="button" onclick="confirmDelete(this.closest(\'form\'))" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">HAPUS</button>';
                    $html .= '</div>';
                    $html .= '</form>';
                    $html .= '</td>';
                    $html .= '</tr>';
                }
            } else {
                $html = '<tr><td colspan="8" class="px-6 py-4 text-center"><div class="bg-gray-500 text-white p-3 rounded shadow-sm">Data Belum Tersedia!</div></td></tr>';
            }

            return response()->json([
                'html' => $html,
                'pagination' => $dataTailors->appends($request->except('page'))->links()->toHtml()
            ]);
        }

        return view('admin.data-tailors.index', compact('dataTailors'))
            ->with('i', ($page - 1) * $entries);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.data-tailors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_koordinator' => 'required|string|max:255|exists:coordinator_codes,nama_koordinator',
                'kode_koordinator' => 'required|string|max:255|exists:coordinator_codes,kode',
                'nama_daerah' => 'required|string|max:255|exists:area_codes,nama_daerah',
                'nama_penjahit' => 'required|string|max:255|exists:tailor_codes,nama_penjahit',
                'kode_penjahit' => 'required|string|max:255|exists:tailor_codes,kode_penjahit'
            ]);

            $dataTailor = new DataTailor([
                'nama_koordinator' => $request->nama_koordinator,
                'kode_koordinator' => $request->kode_koordinator,
                'nama_daerah' => $request->nama_daerah,
                'nama_penjahit' => $request->nama_penjahit,
                'kode_penjahit' => $request->kode_penjahit,
            ]);

            $dataTailor->save();

            return redirect()->route('data-tailors.index')->with('success', 'Data Tailor ' . $dataTailor->nama_koordinator . ' created successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('data-tailors.create')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DataTailor $dataTailor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataTailor $dataTailor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataTailor $dataTailor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataTailor $dataTailor)
    {
        if ($dataTailor) {
            $dataTailor->delete();
            return redirect()->route('data-tailors.index')->with('success', 'Data Tailor ' . $dataTailor->nama_koordinator . ' deleted successfully');
        } else {
            return redirect()->route('data-tailors.index')->with('error', 'Data Tailor not found.');
        }
    }

    // API
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
        $cacheKey = "data_tailors" . ($search ? md5($search) : 'full');
        $ttl = now()->addMinutes(10);

        $data = Cache::remember($cacheKey, $ttl, function () use ($search) {
            // Cari hanya jika ada search query
            $hasSearch = $search && strlen($search) >= 2;

            // Tailor Codes
            $tailorCodeQuery = TailorCode::select([
                'nama_koordinator',
                'kode_koordinator',
                'nama_daerah',
                'nama_penjahit',
                'kode_penjahit',
            ]);

            if ($hasSearch) {
                $tailorCodeQuery->where(function ($q) use ($search) {
                    $q->where('kode_penjahit', 'like', "%{$search}%")
                        ->orWhere('nama_penjahit', 'like', "%{$search}%");
                });
            } else {
                $tailorCodeQuery->orderBy('kode_penjahit');
            }

            return $tailorCodeQuery->limit(50)->get()->map(function ($item) {
                return [
                    'value' => $item->kode_penjahit,
                    'text' => "{$item->nama_koordinator} | {$item->kode_penjahit}",
                    'kode_penjahit' => $item->kode_penjahit,
                    'nama_penjahit' => $item->nama_penjahit,
                    'kode_koordinator' => $item->kode_koordinator,
                    'nama_koordinator' => $item->nama_koordinator,
                    'nama_daerah' => $item->nama_daerah,
                ];
            })->toArray();

        });

        // Baru wrap dengan response di luar cache
        return response()->json($data)->header('Cache-Control', 'public, max-age=60') // Browser cache 60 detik
            ->header('ETag', md5(json_encode($data))) // Untuk validation
            ->header('Last-Modified', now()->format(DateTimeInterface::RFC7231));
    }
}
