<?php

namespace App\Observers;

use App\Models\DataExpense;
use App\Models\SummaryExpense;
use App\Models\SummaryExpenseDetail;
use Illuminate\Support\Facades\DB;

class DataExpenseObserver
{

    /**
     * Handle the DataExpense "updated" event.
     */
    public function updated(DataExpense $dataExpense): void
    {
        $dirty = $dataExpense->getDirty();

        // Field yang mempengaruhi summary
        $affectedFields = ['kredit', 'kategori', 'tanggal_nota'];

        // Cek apakah ada perubahan pada field yang mempengaruhi summary
        $hasAffectedChange = count(array_intersect(array_keys($dirty), $affectedFields)) > 0;

        if (!$hasAffectedChange) {
            return; // Tidak ada perubahan yang mempengaruhi summary
        }

        // 1. Update summary expense details yang sudah ada
        $this->updateRelatedSummaryDetails($dataExpense, $dirty);

        // 2. Jika tanggal_nota berubah, cek apakah perlu dipindahkan ke summary lain
        if (array_key_exists('tanggal_nota', $dirty)) {
            $this->handleDateChange($dataExpense);
        }

        // 3. Recalculate total untuk semua summary yang terpengaruh
        $this->recalculateAffectedSummaries($dataExpense);
    }

    /**
     * Handle the DataExpense "deleted" event.
     */
    public function deleting(DataExpense $dataExpense): void
    {
        // Hapus semua summary expense details yang terkait
        $summaryDetails = SummaryExpenseDetail::where('data_expenses_id', $dataExpense->id)->get();

        foreach ($summaryDetails as $detail) {
            $summaryExpenseId = $detail->summary_expenses_id;
            $detail->delete();

            // Recalculate total untuk summary yang terpengaruh
            $this->recalculateSummaryTotal($summaryExpenseId);
        }
    }

    /**
     * Update summary expense details yang terkait dengan data expense ini
     */
    protected function updateRelatedSummaryDetails(DataExpense $dataExpense, array $dirty): void
    {
        $summaryDetails = SummaryExpenseDetail::where('data_expenses_id', $dataExpense->id)->get();

        foreach ($summaryDetails as $detail) {
            $updateData = [];

            // Update kredit jika berubah
            if (array_key_exists('kredit', $dirty)) {
                $updateData['total_uang_keluar'] = $dataExpense->kredit;
            }

            // Update kategori jika berubah
            if (array_key_exists('kategori', $dirty)) {
                $updateData['kategori'] = $dataExpense->kategori;
            }

            // Update jika ada perubahan
            if (!empty($updateData)) {
                $detail->update($updateData);
            }
        }
    }

    /**
     * Handle perubahan tanggal_nota
     * Cek apakah data expense masih dalam rentang summary yang ada
     */
    protected function handleDateChange(DataExpense $dataExpense): void
    {
        $summaryDetails = SummaryExpenseDetail::where('data_expenses_id', $dataExpense->id)->get();

        foreach ($summaryDetails as $detail) {
            $summaryExpense = $detail->summaryExpense;

            // Cek apakah tanggal baru masih dalam rentang summary
            $tanggalNota = $dataExpense->tanggal_nota;
            $tanggalMulai = $summaryExpense->tanggal_mulai;
            $tanggalAkhir = $summaryExpense->tanggal_akhir;

            // Normalisasi tanggal untuk perbandingan (hilangkan waktu)
            $tanggalNotaStr = $tanggalNota instanceof \DateTime ? $tanggalNota->format('Y-m-d') : $tanggalNota;
            $tanggalMulaiStr = $tanggalMulai instanceof \DateTime ? $tanggalMulai->format('Y-m-d') : $tanggalMulai;
            $tanggalAkhirStr = $tanggalAkhir instanceof \DateTime ? $tanggalAkhir->format('Y-m-d') : $tanggalAkhir;

            // Cek apakah masih dalam range
            // Case 1: Kedua tanggal ada (rentang normal)
            // Case 2: Hanya tanggal_mulai (dari tanggal X sampai selamanya)
            // Case 3: Hanya tanggal_akhir (sampai dengan tanggal X)
            // Case 4: Keduanya NULL (tidak mungkin, tapi handle untuk safety)

            $isInRange = false;

            if ($tanggalMulaiStr !== null && $tanggalAkhirStr !== null) {
                // Case 1: Rentang normal (kedua tanggal ada)
                $isInRange = ($tanggalNotaStr >= $tanggalMulaiStr) && ($tanggalNotaStr <= $tanggalAkhirStr);
            } elseif ($tanggalMulaiStr !== null && $tanggalAkhirStr === null) {
                // Case 2: Hanya tanggal_mulai (dari tanggal X sampai selamanya)
                $isInRange = ($tanggalNotaStr >= $tanggalMulaiStr);
            } elseif ($tanggalMulaiStr === null && $tanggalAkhirStr !== null) {
                // Case 3: Hanya tanggal_akhir (sampai dengan tanggal X)
                $isInRange = ($tanggalNotaStr <= $tanggalAkhirStr);
            } else {
                // Case 4: Keduanya NULL (semua tanggal diterima)
                $isInRange = true;
            }

            if (!$isInRange) {
                // Jika tidak dalam rentang, hapus dari summary ini
                $detail->delete();

                // Recalculate total untuk summary yang ditinggalkan
                $this->recalculateSummaryTotal($summaryExpense->id);

                // Cek apakah ada summary lain yang cocok
                $this->addToRelevantSummaries($dataExpense);
            }
        }
    }

    /**
     * Tambahkan data expense ke summary yang relevan (yang mencakup tanggal ini)
     */
    protected function addToRelevantSummaries(DataExpense $dataExpense): void
    {
        // Cari summary expenses yang mencakup tanggal ini
        // Handle 4 kasus:
        // 1. Rentang normal (kedua tanggal ada)
        // 2. Hanya tanggal_mulai (dari tanggal X sampai selamanya)
        // 3. Hanya tanggal_akhir (sampai dengan tanggal X)
        // 4. Keduanya NULL (semua tanggal)

        $relevantSummaries = SummaryExpense::where(function ($query) use ($dataExpense) {
            $query
                // Case 1: Rentang normal (tanggal_nota dalam range)
                ->where(function ($q) use ($dataExpense) {
                    $q->whereNotNull('tanggal_mulai')
                        ->whereNotNull('tanggal_akhir')
                        ->where('tanggal_mulai', '<=', $dataExpense->tanggal_nota)
                        ->where('tanggal_akhir', '>=', $dataExpense->tanggal_nota);
                })
                // Case 2: Hanya tanggal_mulai (dari tanggal X sampai selamanya)
                ->orWhere(function ($q) use ($dataExpense) {
                    $q->whereNotNull('tanggal_mulai')
                        ->whereNull('tanggal_akhir')
                        ->where('tanggal_mulai', '<=', $dataExpense->tanggal_nota);
                })
                // Case 3: Hanya tanggal_akhir (sampai dengan tanggal X)
                ->orWhere(function ($q) use ($dataExpense) {
                    $q->whereNull('tanggal_mulai')
                        ->whereNotNull('tanggal_akhir')
                        ->where('tanggal_akhir', '>=', $dataExpense->tanggal_nota);
                })
                // Case 4: Keduanya NULL (semua tanggal diterima)
                ->orWhere(function ($q) {
                    $q->whereNull('tanggal_mulai')
                        ->whereNull('tanggal_akhir');
                });
        })->get();

        foreach ($relevantSummaries as $summary) {
            // Cek apakah sudah ada detail untuk data expense ini di summary ini
            $exists = SummaryExpenseDetail::where('summary_expenses_id', $summary->id)
                ->where('data_expenses_id', $dataExpense->id)
                ->exists();

            if (!$exists) {
                // Tambahkan sebagai detail baru
                $lastUrutan = SummaryExpenseDetail::where('summary_expenses_id', $summary->id)
                    ->max('urutan') ?? 0;

                SummaryExpenseDetail::create([
                    'summary_expenses_id' => $summary->id,
                    'data_expenses_id' => $dataExpense->id,
                    'kategori' => $dataExpense->kategori,
                    'total_uang_keluar' => $dataExpense->kredit,
                    'urutan' => $lastUrutan + 1,
                ]);

                // Recalculate total
                $this->recalculateSummaryTotal($summary->id);
            }
        }
    }

    /**
     * Recalculate total untuk semua summary yang terpengaruh oleh perubahan data expense
     */
    protected function recalculateAffectedSummaries(DataExpense $dataExpense): void
    {
        // Ambil semua summary yang memiliki detail dari data expense ini
        $summaryIds = SummaryExpenseDetail::where('data_expenses_id', $dataExpense->id)
            ->pluck('summary_expenses_id')
            ->unique();

        foreach ($summaryIds as $summaryId) {
            $this->recalculateSummaryTotal($summaryId);
        }
    }

    /**
     * Recalculate total keseluruhan untuk summary expense
     */
    protected function recalculateSummaryTotal(int $summaryExpenseId): void
    {
        $total = SummaryExpenseDetail::where('summary_expenses_id', $summaryExpenseId)
            ->sum('total_uang_keluar');

        SummaryExpense::where('id', $summaryExpenseId)
            ->update(['total_keseluruhan' => $total]);
    }

    /**
     * Handle the DataExpense "restored" event.
     * Dipanggil jika menggunakan soft deletes dan data di-restore
     */
    public function restored(DataExpense $dataExpense): void
    {
        // Tambahkan kembali ke summary yang relevan
        $this->addToRelevantSummaries($dataExpense);
    }

    /**
     * Handle the DataExpense "force deleted" event.
     * Dipanggil jika data benar-benar dihapus dari database
     */
    public function forceDeleted(DataExpense $dataExpense): void
    {
        // Sama seperti deleted
        $this->deleting($dataExpense);
    }
}