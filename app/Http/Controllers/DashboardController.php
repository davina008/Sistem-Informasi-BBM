<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter
        $filterDivisi   = $request->divisi ?? 'all';
        $filterPeriode  = $request->periode ?? 'tahun_ini';
        $filterTahun    = $request->tahun ?? date('Y');

        // Custom date tetap diterima tapi tidak akan dipakai (karena tabel anggaran tidak punya kolom tanggal)
        $startDate = $request->start;
        $endDate   = $request->end;

        // Ambil daftar divisi
        $divisis = DB::table('divisi')->get();

        // Ambil tahun unik dari tabel anggaran
        $years = DB::table('anggaran')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        /*
        |--------------------------------------------------------------------------
        | 1. Base Query Anggaran
        |--------------------------------------------------------------------------
        */
        $base = DB::table('anggaran')
            ->join('kendaraan', 'anggaran.id_kendaraan', '=', 'kendaraan.id_kendaraan')
            ->leftJoin('penanggung_jawab as pj', 'kendaraan.id_penanggung_jawab', '=', 'pj.id_penanggung_jawab')
            ->leftJoin('divisi', 'pj.id_divisi', '=', 'divisi.id_divisi')
            ->select(
                'anggaran.*',
                'kendaraan.no_plat',
                'kendaraan.merek',
                'divisi.nama_divisi'
            );

        // Filter Divisi
        if ($filterDivisi !== 'all') {
            $base->where('divisi.id_divisi', $filterDivisi);
        }

        // Filter Periode
        if ($filterPeriode === 'tahun_ini') {
            $base->where('anggaran.tahun', date('Y'));
        } elseif ($filterPeriode === 'bulan_ini') {
            // Tidak ada tanggal → fallback ke tahun ini
            $base->where('anggaran.tahun', date('Y'));
        } elseif ($filterPeriode === 'tahun') {
            $base->where('anggaran.tahun', $filterTahun);
        } elseif ($filterPeriode === 'custom') {
            // Karena tabel tidak punya tanggal, custom tidak bisa digunakan
            // Jadi kita tidak menambahkan filter apa pun
        }

        // Eksekusi query
        $dataAnggaran = $base->get();

        /*
        |--------------------------------------------------------------------------
        | 2. Statistik Ringkasan
        |--------------------------------------------------------------------------
        */
        // Total Divisi
if ($filterDivisi === 'all') {
    $totalDivisi = DB::table('divisi')->count();
} else {
    $totalDivisi = 1; // karena hanya satu divisi yang dipilih
}

// Total Kendaraan (mengikuti relasi divisi → pj → kendaraan)
$kendaraanQuery = DB::table('kendaraan')
    ->join('penanggung_jawab as pj', 'kendaraan.id_penanggung_jawab', '=', 'pj.id_penanggung_jawab');

if ($filterDivisi !== 'all') {
    $kendaraanQuery->where('pj.id_divisi', $filterDivisi);
}

$totalKendaraan = $kendaraanQuery->count();


        $totalPemakaian = $dataAnggaran->sum('pemakaian');

        $totalAnggaran = $dataAnggaran->sum('anggaran_total');

        /*
        |--------------------------------------------------------------------------
        | 3. Ringkasan per Kendaraan
        |--------------------------------------------------------------------------
        */
        $anggaranPerKendaraan = $dataAnggaran->map(function ($a) {
            return [
                'no_plat'        => $a->no_plat,
                'merek'          => $a->merek,
                'nama_divisi'    => $a->nama_divisi,
                'anggaran_total' => $a->anggaran_total,
                'pemakaian'      => $a->pemakaian,
                'sisa'           => $a->sisa,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Kirim ke View
        |--------------------------------------------------------------------------
        */
        return view('dashboard', [
            'divisis'             => $divisis,
            'years'               => $years,
            'filterDivisi'        => $filterDivisi,
            'filterPeriode'       => $filterPeriode,
            'filterTahun'         => $filterTahun,
            'startDate'           => $startDate,
            'endDate'             => $endDate,
            'anggaranPerKendaraan'=> $anggaranPerKendaraan,
            'totalDivisi'         => $totalDivisi,
            'totalKendaraan'      => $totalKendaraan,
            'totalPemakaian'      => $totalPemakaian,
            'totalAnggaran'       => $totalAnggaran,
        ]);
    }
}
