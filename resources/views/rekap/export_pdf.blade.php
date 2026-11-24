<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap BBM</title>
    <style>
        @page {
            margin: 30px 35px;
        }
        body {
            font-family: "Times New Roman" , serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .kop-table {
            width: 100%;
            border: none;
            margin-bottom: 8px;
        }
        .kop-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }
        .kop-logo {
            width: 75px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            line-height: 1.4;
        }
        .kop-text .instansi {
            font-size: 14px;
            font-weight: bold;
        }
        .kop-text .dinas {
            font-size: 16px;
            font-weight: bold;
        }
        .kop-text .alamat {
            font-size: 11px;
            margin-top: 2px;
        }
        hr {
            border: 1.5px solid #000;
            margin: 6px 0 12px 0;
        }
        .right {
            text-align: right;
        }
        .center {
            text-align: center;
        }
        .signature {
            margin-top: 70px;
            width: 100%;
            text-align: center;
            font-size: 11.5px;
        }
        .signature .name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
        .signature .nip {
            margin-top: 4px;
        }
    </style>
</head>
<body>

    {{-- === HEADER / KOP SURAT === --}}
    <table class="kop-table">
    <tr>
        <!-- Logo kiri -->
        <td width="15%" style="text-align: center;">
            @php
                $logoSleman = public_path('logo/LOGO_SLEMAN.png');
            @endphp
            @if(file_exists($logoSleman))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoSleman)) }}" class="kop-logo">
            @else
                <div style="font-size:10px; color:red;">Logo Sleman tidak ditemukan</div>
            @endif
        </td>

        <!-- Teks tengah -->
        <td width="70%" class="kop-text">
            <div class="instansi">PEMERINTAH KABUPATEN SLEMAN</div>
            <div class="dinas">DINAS KEBUDAYAAN (KUNDHA KABUDAYAN) SLEMAN</div>
            <div class="alamat">
                Jl. KRT. Pringgodiningrat No.11, Beran, Tridadi, Sleman, Yogyakarta 55511<br>
                Telp. (0274) 868505 • Email: kebudayaan@slemankab.go.id
            </div>
        </td>

        <!-- Kolom kanan kosong untuk menyeimbangkan posisi -->
        <td width="15%"></td>
    </tr>
</table>

<hr>


    {{-- === JUDUL LAPORAN === --}}
    <div class="center" style="margin-top: 5px;">
        <div style="font-weight: bold; font-size: 14px;">LAPORAN REKAP PENGELUARAN BBM</div>
        <div>Dinas Kebudayaan (Kundha Kabudayan) Kabupaten Sleman</div>

        <div style="margin-top: 4px;">
            @php
                use Carbon\Carbon;

                if ($bulan) {
                    try {
                        $namaBulan = Carbon::createFromDate(null, (int) $bulan, 1)->translatedFormat('F');
                    } catch (\Throwable $e) {
                        $namaBulan = 'Bulan Tidak Dikenal';
                    }
                } else {
                    $namaBulan = 'Semua Bulan';
                }
            @endphp

            Bulan: <b>{{ $namaBulan }}</b>
            @if($tahun)
                , Tahun: <b>{{ $tahun }}</b>
            @endif
            @if($plat)
                , Plat Kendaraan: <b>{{ strtoupper($plat) }}</b>
            @endif
        </div>
    </div>

    {{-- === TABEL DATA === --}}
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">ID Rekap</th>
                <th style="width: 15%;">ID Anggaran</th>
                <th style="width: 15%;">No. Plat</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 20%;">Pengeluaran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekaps as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $r->id_rekap }}</td>
                    <td>{{ $r->id_anggaran }}</td>
                    <td>{{ $r->anggaran->kendaraan->no_plat ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->tgl)->format('d/m/Y') }}</td>
                    <td class="right">{{ number_format($r->pengeluaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data rekap.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight:bold; background-color:#f2f2f2;">
                <td colspan="5" class="right">Total Pengeluaran:</td>
                <td class="right">{{ number_format($rekaps->sum('pengeluaran'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- === TANDA TANGAN === --}}
    <div class="signature" style="float: right; width: 40%; page-break-inside: avoid;">
        Sleman, {{ now()->translatedFormat('d F Y') }}<br>
        Kepala Dinas Kebudayaan Sleman<br><br><br><br>
        <div class="name">_________________________</div>
        <div class="nip">NIP. _______________________</div>
    </div>

</body>
</html>
