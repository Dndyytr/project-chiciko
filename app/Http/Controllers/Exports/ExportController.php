<?php

namespace App\Http\Controllers\Exports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\SummaryExpense;
use Carbon\Carbon;


class ExportController extends Controller
{

    /**
     * Export data dari satu tabel ke Excel
     * Bisa digunakan untuk semua tabel yang diizinkan
     */
    public function export($table)
    {
        // Daftar tabel yang diizinkan untuk diekspor
        $allowedTables = [
            'incoming_raw_materials',
            'incoming_complement_materials',
            'stock_opnames',
            'stock_raw_materials',
            'stock_complement_materials',
            'data_expenses',
        ];

        // Validasi: pastikan tabel yang diminta ada dalam daftar yang diizinkan
        if (!in_array($table, $allowedTables)) {
            abort(404, 'Tabel tidak ditemukan'); // Hentikan proses jika tabel tidak valid
        }

        // Buat writer Spout untuk format XLSX
        $writer = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createXLSXWriter();

        // Buka koneksi langsung ke browser untuk download
        $writer->openToBrowser("{$table} " . now()->format('Y-m-d H-i-s') . '.xlsx');

        try {
            // Dapatkan nama kolom dari database secara otomatis
            $columns = Schema::getColumnListing($table);

            // Buat baris header menggunakan nama kolom dari database
            $headerRow = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($columns);
            $writer->addRow($headerRow); // Tambahkan header ke file Excel

            // Query data dari tabel yang diminta
            DB::table($table)
                ->where('created_at', '>=', now()->subDays(365)) // Filter data 1 tahun terakhir
                ->orderBy('id') // Urutkan berdasarkan ID
                ->chunk(1000, function ($rows) use ($writer, $columns) { // Proses 1.000 baris per chunk
                    $rowsArray = []; // Array sementara untuk menyimpan baris
    
                    foreach ($rows as $row) {
                        $rowData = []; // Array untuk data satu baris
                        foreach ($columns as $column) {
                            $value = $row->{$column} ?? ''; // Ambil nilai kolom, default kosong jika null
    
                            // Format nilai berdasarkan tipe data
                            $value = $this->formatCellValue($column, $value);

                            $rowData[] = $value; // Tambahkan nilai ke array baris
                        }

                        // Buat objek row Spout dan tambahkan ke array
                        $rowsArray[] = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($rowData);
                    }

                    $writer->addRows($rowsArray); // Tambahkan semua baris ke file Excel
                });

        } catch (\Exception $e) {
            \Log::error("Export error for {$table}: " . $e->getMessage());
            abort(500, 'Gagal mengekspor data');
        } finally {
            $writer->close();
        }
    }

    /**
     * Export semua tabel sekaligus ke satu file dengan multiple sheets
     */
    public function exportAll()
    {
        // Daftar tabel yang akan diekspor
        $tables = ['incoming_raw_materials', 'stock_opnames', 'purchase_based_notes'];

        // Buat writer untuk format XLSX
        $writer = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createXLSXWriter();

        // Buka koneksi ke browser untuk download
        $writer->openToBrowser('Database Chiciko ' . now()->format('Y-m-d H-i-s') . '.xlsx');

        try {
            // Loop melalui setiap tabel
            foreach ($tables as $index => $table) {
                if ($index > 0) {
                    $writer->addNewSheetAndMakeItCurrent();
                }

                // Set nama sheet (dari nama tabel, diubah jadi title case)
                $writer->getCurrentSheet()->setName(Str::title(str_replace('_', ' ', $table)));

                // Ekspor data untuk tabel ini
                $this->exportTable($writer, $table);
            }
        } catch (\Exception $e) {
            \Log::error("Multi-export error: " . $e->getMessage());
            abort(500, 'Gagal mengekspor data');
        } finally {
            $writer->close();
        }
    }

    /**
     * Helper function untuk export satu tabel
     * Digunakan oleh exportAll() untuk menghindari duplikasi kode
     */
    private function exportTable($writer, $table)
    {
        // Dapatkan nama kolom dari database
        $columns = Schema::getColumnListing($table);

        // Buat dan tambahkan header
        $headerRow = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($columns);
        $writer->addRow($headerRow);

        // Query dan proses data
        DB::table($table)
            ->where('created_at', '>=', now()->subDays(365))
            ->orderBy('id')
            ->chunk(1000, function ($rows) use ($writer, $columns) {
                $rowsArray = [];

                foreach ($rows as $row) {
                    $rowData = [];
                    foreach ($columns as $column) {
                        $value = $row->{$column} ?? '';

                        // Format nilai berdasarkan tipe data
                        $value = $this->formatCellValue($column, $value);

                        $rowData[] = $value;
                    }
                    $rowsArray[] = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($rowData);
                }

                $writer->addRows($rowsArray);
            });
    }

    /**
     * Export Summary Expenses dengan detail kategori dalam satu tabel
     * Format: No | Tanggal | Kategori dan Keuangan | Total Keseluruhan
     */
    public function exportSummaryExpenses()
    {
        try {
            // Buat writer Spout untuk format XLSX
            $writer = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createXLSXWriter();
            $writer->openToBrowser('Summary Expenses ' . now()->format('Y-m-d_H-i-s') . '.xlsx');

            // Buat header
            $headers = ['No', 'Tanggal', 'Kategori dan Keuangan', 'Total Keseluruhan'];
            $headerRow = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($headers);
            $writer->addRow($headerRow);

            $no = 1; // Inisialisasi nomor urut

            // Query dengan eager loading
            SummaryExpense::with('summaryExpenseDetails')
                ->orderBy('tanggal_mulai', 'desc')
                ->chunk(1000, function ($summaries) use ($writer, &$no) {
                    $rowsArray = [];

                    foreach ($summaries as $summary) {
                        // Format tanggal dengan Carbon
                        $tanggal = $this->formatTanggalRange($summary);

                        // Format kategori dan keuangan
                        $kategoriKeuangan = collect($summary->summaryExpenseDetails)
                            ->map(function ($detail) {
                            return sprintf(
                                '%s Rp%s',
                                $detail->kategori,
                                format_number_id($detail->total_uang_keluar)
                            );
                        })
                            ->implode(' | ');

                        // Buat row data dengan nomor urut
                        $rowData = [
                            $no, // Gunakan nomor urut, bukan ID
                            $tanggal,
                            $kategoriKeuangan ?: '-',
                            'Rp' . format_number_id($summary->total_keseluruhan)
                        ];

                        $rowsArray[] = \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createRowFromArray($rowData);
                        $no++; // Increment nomor urut
                    }

                    // Tulis rows setiap chunk
                    if (!empty($rowsArray)) {
                        $writer->addRows($rowsArray);
                        $rowsArray = []; // Reset array
                    }
                });

        } catch (\Box\Spout\Common\Exception\SpoutException $e) {
            \Log::error("Export error (Spout): " . $e->getMessage());
            abort(500, 'Gagal membuat file Excel');
        } catch (\Exception $e) {
            \Log::error("Export error (General): " . $e->getMessage());
            abort(500, 'Gagal mengekspor data');
        } finally {
            if (isset($writer)) {
                $writer->close();
            }
        }
    }

    /**
     * Format tanggal range untuk export
     */
    private function formatTanggalRange($summary): string
    {
        if ($summary->tanggal_mulai && $summary->tanggal_akhir) {
            return Carbon::parse($summary->tanggal_mulai)->format('d/m/Y') .
                ' - ' .
                Carbon::parse($summary->tanggal_akhir)->format('d/m/Y');
        }

        if ($summary->tanggal_mulai) {
            return 'Dari ' . Carbon::parse($summary->tanggal_mulai)->format('d/m/Y');
        }

        if ($summary->tanggal_akhir) {
            return 'Sampai ' . Carbon::parse($summary->tanggal_akhir)->format('d/m/Y');
        }

        return 'Semua Tanggal';
    }

    /**
     * Format nilai cell berdasarkan tipe data
     * Mendeteksi dan memformat angka, tanggal, boolean, dll
     */
    private function formatCellValue($column, $value)
    {
        // Jika null atau empty string, return as is
        if ($value === null || $value === '') {
            return $value;
        }

        // Skip formatting untuk kolom tertentu (ID, timestamps, dll)
        $skipColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
        if (in_array($column, $skipColumns)) {
            // Format tanggal untuk timestamps
            if (in_array($column, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2}(\.\d+)?Z)?$/', $value)) {
                    $timestamp = strtotime($value);
                    if ($timestamp !== false) {
                        return date('Y-m-d H:i:s', $timestamp);
                    }
                }
            }
            return $value;
        }

        // Format angka menggunakan helper format_number_id()
        if (is_numeric($value)) {
            return format_number_id($value);
        }

        // Format tanggal (selain timestamps)
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2}(\.\d+)?Z)?$/', $value)) {
            $timestamp = strtotime($value);
            if ($timestamp !== false) {
                return date('Y-m-d H:i:s', $timestamp);
            }
        }

        // Format boolean
        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        // Return value as is jika tidak ada format khusus
        return $value;
    }
}
