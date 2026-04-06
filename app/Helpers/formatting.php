<?php

// format untuk seluruh angka
if (!function_exists('format_number_id')) {
    function format_number_id($angka)
    {
        if (!is_numeric($angka))
            return $angka;

        $parts = explode('.', (string) $angka);
        $formatted = number_format((float) $parts[0], 0, ',', '.');
        if (isset($parts[1]) && rtrim($parts[1], '0') !== '') {
            $formatted .= ',' . rtrim($parts[1], '0');
        }
        return $formatted;
    }
}

// format dari seluruh angka diatas dengan tambahan Rp
if (!function_exists('format_rupiah_id')) {
    function format_rupiah_id($angka)
    {
        return 'Rp ' . format_number_id($angka);
    }
}

// fomrat tanggal
use Carbon\Carbon;

if (!function_exists('format_tanggal_id')) {
    /**
     * Format tanggal dalam gaya Indonesia
     *
     * @param string|Carbon|null $tanggal
     * @param bool $withTime  => apakah menampilkan jam (H:i:s)
     * @param bool $withDay   => apakah menampilkan nama hari
     * @return string
     */
    function format_tanggal_id($tanggal, $withTime = false, $withDay = false)
    {
        if (!$tanggal)
            return '-';

        try {
            $date = Carbon::parse($tanggal);
        } catch (\Exception $e) {
            return $tanggal;
        }

        // Nama hari dan bulan dalam bahasa Indonesia
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Format dasar
        $formatted = $date->day . ' ' . $bulan[$date->month] . ' ' . $date->year;

        // Tambahkan jam jika diminta
        if ($withTime) {
            $formatted .= ' ' . $date->format('H:i:s');
        }

        // Tambahkan nama hari jika diminta
        if ($withDay) {
            $dayName = $hari[$date->format('l')];
            $formatted = $dayName . ', ' . $formatted;
        }

        return $formatted;
    }
}