<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan SIMABBM</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            margin: 25px;
        }

        /* ==================== KOP SURAT TANPA BORDER ==================== */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            border: none !important;   /* Hilangkan border */
        }

        .kop-text {
            text-align: center;
            line-height: 1.3;
        }

        .kop-text h3 {
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        .kop-text p {
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .logo-img {
            width: 90px;
            height: auto;
        }

        hr {
            border: 1px solid #000;
            margin: 10px 0 20px 0;
        }

        /* ==================== TABLE DATA (ADA BORDER) ==================== */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table.data th, table.data td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11.5px;
            text-align: center;
        }

        table.data th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        h4, h5 {
            text-align: center;
            margin: 5px 0;
        }

        /* ==================== FOOTER ==================== */
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }

        .ttd {
            margin-top: 70px;
            text-align: right;
        }

        .ttd p {
            margin: 2px 0;
        }
    </style>
</head>
<body>

    <!-- ========================= KOP SURAT ========================= -->
    <table style="width:100%; border-collapse: collapse;">
    <tr>
        <!-- LOGO -->
        <td style="width: 20%; text-align: right; vertical-align: middle;">
            @if($logoBase64Kiri)
                <img src="{{ $logoBase64Kiri }}" style="width:85px;">
            @endif
        </td>

        <!-- TEKS KOP SURAT -->
        <td style="width: 80%; text-align: center; vertical-align: middle; padding-left: -50px;">
            <h3 style="margin:0;">PEMERINTAH KABUPATEN SLEMAN</h3>
            <h3 style="margin:0;">DINAS KEBUDAYAAN (KUNDHA KABUDAYAN)</h3>
            <p style="margin:0;">Jl. KRT. Pringgodiningrat No.13, Beran, Tridadi, Sleman, Yogyakarta 55511</p>
            <p style="margin:0;">Telp. (0274) 868505 • Email: kundhakabudayan@slemankab.go.id</p>
        </td>
    </tr>
</table>


    <hr>

    <!-- ========================= JUDUL LAPORAN ========================= -->
    <h4><u>LAPORAN SISTEM INFORMASI MANAJEMEN ANGGARAN BBM</u></h4>
    <h5>Dinas Kebudayaan (Kundha Kabudayan) Kabupaten Sleman</h5>
    <br>

    <!-- ========================= DATA DIVISI ========================= -->
    <h4>Data Divisi</h4>
    <table class="data">
        <thead>
            <tr>
                <th>ID Divisi</th>
                <th>Nama Divisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($divisi as $d)
                <tr>
                    <td>{{ $d->id_divisi }}</td>
                    <td>{{ $d->nama_divisi }}</td>
                </tr>
            @empty
                <tr><td colspan="2">Tidak ada data divisi</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========================= DATA PENANGGUNG JAWAB ========================= -->
    <h4>Data Penanggung Jawab</h4>
    <table class="data">
        <thead>
            <tr>
                <th>ID Penanggung Jawab</th>
                <th>Nama Penanggung Jawab</th>
                <th>Divisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penanggungJawab as $p)
                <tr>
                    <td>{{ $p->id_penanggung_jawab }}</td>
                    <td>{{ $p->nama_penanggung_jawab }}</td>
                    <td>{{ $p->divisi->nama_divisi ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Tidak ada data penanggung jawab</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========================= DATA KENDARAAN ========================= -->
    <h4>Data Kendaraan</h4>
    <table class="data">
        <thead>
            <tr>
                <th>ID Kendaraan</th>
                <th>No. Plat</th>
                <th>Merek</th>
                <th>Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kendaraan as $k)
                <tr>
                    <td>{{ $k->id_kendaraan }}</td>
                    <td>{{ $k->no_plat }}</td>
                    <td>{{ $k->merek }}</td>
                    <td>{{ $k->penanggungJawab->nama_penanggung_jawab ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada data kendaraan</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========================= DATA ANGGARAN ========================= -->
    <h4>Data Anggaran</h4>
    <table class="data">
        <thead>
            <tr>
                <th>ID Anggaran</th>
                <th>Tahun</th>
                <th>No. Plat Kendaraan</th>
                <th>Total Anggaran (Rp)</th>
                <th>Pemakaian (Rp)</th>
                <th>Sisa (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggaran as $a)
                <tr>
                    <td>{{ $a->id_anggaran }}</td>
                    <td>{{ $a->tahun }}</td>
                    <td>{{ $a->kendaraan->no_plat ?? '-' }}</td>
                    <td style="text-align:right">{{ number_format($a->anggaran_total, 0, ',', '.') }}</td>
                    <td style="text-align:right">{{ number_format($a->pemakaian, 0, ',', '.') }}</td>
                    <td style="text-align:right">{{ number_format($a->sisa, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Tidak ada data anggaran</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========================= DATA REKAP ========================= -->
    <h4>Data Rekap</h4>
    <table class="data">
        <thead>
            <tr>
                <th>ID Rekap</th>
                <th>ID Anggaran</th>
                <th>Total Pemakaian (Rp)</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $r)
                <tr>
                    <td>{{ $r->id_rekap }}</td>
                    <td>{{ $r->anggaran->id_anggaran ?? '-' }}</td>
                    <td style="text-align:right">{{ number_format($r->total_pemakaian, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada data rekap</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========================= FOOTER ========================= -->
    <div class="footer">
        <p>Sleman, {{ $tanggalCetak }}</p>
        <p><strong>Dinas Kebudayaan (Kundha Kabudayan)</strong></p>

        <div class="ttd">
            <p><u>(__________________________)</u></p>
            <p>NIP. ________________________</p>
        </div>
    </div>

</body>
</html>
