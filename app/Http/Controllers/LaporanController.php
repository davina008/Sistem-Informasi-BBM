<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Divisi;
use App\Models\PenanggungJawab;
use App\Models\Kendaraan;
use App\Models\Anggaran;
use App\Models\Rekap;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * 🧾 Export semua data (Divisi, Penanggung Jawab, Kendaraan, Anggaran, Rekap)
     * menjadi satu file PDF lengkap dengan logo & kop surat.
     */
    public function exportSemuaDataPDF()
    {
        try {
            // Ambil seluruh data beserta relasinya
            $divisi = Divisi::all();
            $penanggungJawab = PenanggungJawab::with('divisi')->get();
            $kendaraan = Kendaraan::with('penanggungJawab.divisi')->get();
            $anggaran = Anggaran::with('kendaraan.penanggungJawab.divisi')->get();
            $rekap = Rekap::with('anggaran.kendaraan.penanggungJawab.divisi')->get();

            // Path logo
            $logoPathKiri = public_path('logo/LOGO_SLEMAN.png');
            $logoPathKanan = public_path('logo/LOGO_DINAS.png');

            // Convert to Base64
            $logoBase64Kiri = $this->getBase64Image($logoPathKiri);
            $logoBase64Kanan = $this->getBase64Image($logoPathKanan);

            // Format tanggal Indonesia
            $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

            // Set global DomPDF options
            Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 120,
                'defaultFont' => 'Times-Roman',
            ]);

            // Generate PDF
            $pdf = Pdf::loadView('laporan.semua_data_pdf', [
                'divisi' => $divisi,
                'penanggungJawab' => $penanggungJawab,
                'kendaraan' => $kendaraan,
                'anggaran' => $anggaran,
                'rekap' => $rekap,
                'logoBase64Kiri' => $logoBase64Kiri,
                'logoBase64Kanan' => $logoBase64Kanan,
                'tanggalCetak' => $tanggalCetak,
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 15)
            ->setOption('margin-bottom', 15)
            ->setOption('margin-left', 12)
            ->setOption('margin-right', 12);

            return $pdf->stream('Laporan_Semua_Data.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }




    /**
     * ✅📊 Export laporan data anggaran saja (HASIL REVISI — hanya logo kiri = LOGO_DINAS)
     */
    public function exportAnggaranPDF()
    {
        try {
            // Ambil data anggaran beserta relasinya
            $anggaran = Anggaran::with('kendaraan.penanggungJawab.divisi')->get();

            // Path logo kiri (HANYA LOGO DINAS)
            $logoPathKiri = public_path('logo/LOGO_DINAS.png');

            // Convert base64
            $logoBase64Kiri = $this->getBase64Image($logoPathKiri);

            // Logo kanan tidak dipakai
            $logoBase64Kanan = null;

            // Tanggal
            $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

            // Set PDF
            Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 120,
                'defaultFont' => 'Times-Roman',
            ]);

            // Generate
            $pdf = Pdf::loadView('admin.laporan.anggaran_pdf', [
                'anggaran' => $anggaran,
                'logoBase64Kiri' => $logoBase64Kiri,
                'logoBase64Kanan' => $logoBase64Kanan, // tetap dikirim supaya blade tidak error
                'tanggalCetak' => $tanggalCetak,
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 15)
            ->setOption('margin-bottom', 15)
            ->setOption('margin-left', 12)
            ->setOption('margin-right', 12);

            return $pdf->download('Laporan_Anggaran_BBM.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }




    /**
     * 🔧 Convert image to Base64 (untuk DomPDF)
     */
    private function getBase64Image($path)
    {
        if (!file_exists($path)) {
            return null;
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
