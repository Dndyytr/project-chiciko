<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelDataController extends Controller
{
    // Daftar tabel yang diizinkan - tambahkan di sini untuk tabel baru
    protected $allowedTables = [
        'incoming_raw_materials' => [
            'columns' => null, // null = ambil semua kolom
            'date_field' => 'created_at', // gunakan kolom created_at untuk filter tanggal
            'limit' => 100000, // maksimal 100.000 baris untuk tabel ini
            'name' => 'Raw Materials' // nama tampilan yang lebih user-friendly
        ],
        'database_materials' => [
            'columns' => null,
            'date_field' => 'created_at',
            'limit' => 100000,
            'name' => 'database materials'
        ],
        'stock_opnames' => [
            'columns' => null,
            'date_field' => 'created_at',
            'limit' => 100000,
            'name' => 'Stock Opnames'
        ],
        'stock_raw_materials' => [
            'columns' => null,
            'date_field' => 'created_at',
            'limit' => 100000,
            'name' => 'Stock Raw Materials'
        ],
        // Tambahkan tabel baru di sini
        // 'nama_tabel' => ['columns' => null, 'date_field' => 'created_at', 'limit' => 5000, 'name' => 'Nama Tampilan']
    ];

    /**
     * Stream data ke client secara real-time
     * Menggunakan teknik streaming + chunking untuk efisiensi memory
     */
    public function getAllData(Request $request)
    {
        // Validasi token sederhana
        if ($request->key !== env('EXCEL_API_KEY', 'chiciko')) {
            return response()->json(['error' => 'Invalid Key'], 401);
        }

        // Ambil dan proses parameter tables
        $tablesParam = $request->input('tables');
        $tables = [];

        if ($tablesParam === null) {
            // Gunakan semua tabel jika tidak ada parameter
            $tables = array_keys($this->allowedTables);
        } elseif (is_string($tablesParam)) {
            // Pecah string dengan koma
            $tables = array_filter(array_map('trim', explode(',', $tablesParam)));
        } elseif (is_array($tablesParam)) {
            // Gunakan array langsung
            $tables = array_filter($tablesParam);
        } else {
            // Default: semua tabel
            $tables = array_keys($this->allowedTables);
        }

        // Filter tabel yang diminta, hanya yang ada di allowedTables yang akan diproses
        $validTables = array_intersect($tables, array_keys($this->allowedTables));

        // Jika tidak ada tabel valid, kembalikan error
        if (empty($validTables)) {
            return response()->json(['error' => 'No valid tables specified'], 400);
        }

        // Buat response streaming - data akan dikirim secara bertahap, bukan sekaligus
        $response = new StreamedResponse();

        // Set header Content-Type ke application/json karena kita mengirim JSON
        $response->headers->set('Content-Type', 'application/json');

        // Nonaktifkan buffering Nginx agar data langsung dikirim ke client
        $response->headers->set('X-Accel-Buffering', 'no');

        // Atur cache control agar browser tidak menyimpan cache response
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        // Set connection ke keep-alive agar koneksi tetap terbuka selama streaming
        $response->headers->set('Connection', 'keep-alive');

        // Set callback function yang akan dieksekusi saat response dikirim
        $response->setCallback(function () use ($validTables) {
            echo "{\n"; // Mulai JSON object

            $firstTable = true; // Penanda apakah ini tabel pertama (untuk koma)

            // Loop melalui setiap tabel yang valid
            foreach ($validTables as $table) {
                if (!$firstTable)
                    echo ",\n"; // Tambahkan koma jika bukan tabel pertama
                $firstTable = false;

                $config = $this->allowedTables[$table]; // Ambil konfigurasi tabel

                // Mulai array untuk tabel ini
                echo "\"{$table}\": [\n";

                // Streaming data untuk tabel ini
                $this->streamTableData($table, $config);

                // Tutup array tabel
                echo "\n]";
            }

            // Tutup JSON object utama
            echo "\n}";
        });

        return $response;
    }

    /**
     * Streaming data untuk satu tabel
     * Menggunakan chunking untuk memproses data dalam potongan-potongan kecil
     */
    private function streamTableData($table, $config)
    {
        $printedFirst = false; // Penanda apakah sudah mencetak baris pertama
        $chunkSize = 1000; // Ukuran setiap chunk (1000 baris per proses)
        $maxRecords = $config['limit']; // Batas maksimal baris sesuai konfigurasi
        $count = 0; // Penghitung jumlah baris yang sudah diproses

        // Query database dengan chunking
        DB::table($table)
            ->where($config['date_field'], '>=', now()->subDays(365)) // Filter data 1 tahun terakhir
            ->orderBy('id') // Urutkan berdasarkan ID
            ->chunk($chunkSize, function ($rows) use ($table, $config, &$printedFirst, &$count, $maxRecords) {
                // Loop melalui setiap baris dalam chunk ini
                foreach ($rows as $row) {
                    // Hentikan proses jika sudah mencapai batas maksimal
                    if ($count >= $maxRecords) {
                        return false; // Hentikan proses
                    }

                    // Tambahkan koma jika bukan baris pertama
                    if ($printedFirst)
                        echo ",\n";
                    $printedFirst = true;

                    // Format baris data sesuai kebutuhan Excel
                    $formatted = $this->formatRow($row, $table, $config);

                    // Cetak data yang sudah diformat sebagai JSON
                    echo json_encode($formatted, JSON_UNESCAPED_UNICODE);

                    $count++; // Tambah penghitung
                }

                // Flush output setiap chunk
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            });
    }

    /**
     * Format satu baris data untuk respons API
     * Menyesuaikan format data agar kompatibel dengan Excel
     */
    private function formatRow($row, $table, $config)
    {
        // Dapatkan daftar kolom: dari konfigurasi atau dari schema database
        $columns = $config['columns'] ?? Schema::getColumnListing($table);
        $data = []; // Array untuk menyimpan data yang sudah diformat

        // Loop melalui setiap kolom
        foreach ($columns as $column) {
            if (!isset($row->{$column}))
                continue;

            $value = $row->{$column};

            // Format tanggal
            if ($value && is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2}(\.\d+)?Z)?$/', $value)) {
                $timestamp = strtotime($value);
                if ($timestamp !== false) {
                    $value = date('Y-m-d H:i:s', $timestamp);
                }
            }
            // Format boolean
            elseif (is_bool($value)) {
                $value = $value ? 'Yes' : 'No';
            }
            // Format null
            elseif ($value === null) {
                $value = '';
            }

            $data[$column] = $value;
        }

        return $data;
    }
}