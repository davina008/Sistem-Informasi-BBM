<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Anggaran BBM</title>

    <style>
        @page { margin: 30px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .kop-logo {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* FIX DOMPDF: gambar wajib block dan fixed-width */
        .kop-logo img {
            display: block;
            width: 85px;
            height: auto;
        }

        .placeholder {
            width: 85px;
            height: 85px;
        }

        .kop-text {
            text-align: center;
            width: 100%;
        }

        .kop-text h3 {
            margin: 0;
            font-size: 15px;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .kop-text p {
            margin: 0;
            font-size: 11px;
        }

        /* JUDUL */
        h2.judul {
            text-decoration: underline;
            text-align: center;
            margin-top: 10px;
            font-size: 15px;
        }

        p.subjudul {
            text-align: center;
            font-size: 13px;
            margin-bottom: 10px;
        }

        /* TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        /* FOOTER & TTD */
        .footer {
            margin-top: 30px;
            width: 100%;
            text-align: right;
            font-size: 11px;
        }

        .ttd {
            width: 250px;
            float: right;
            text-align: center;
            margin-top: 40px;
        }

        .ttd p {
            margin: 2px 0;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- ====================== KOP SURAT ======================= -->
    <div class="kop-surat">
        <div class="kop-logo">

            <!-- LOGO KIRI | FIX: tampil hanya jika Base64 valid -->
            @if(!empty($logoBase64Kiri))
                <img src="{{ $logoBase64Kiri }}" alt="Logo Kiri">
            @else
                <div class="placeholder"></div>
            @endif

            <!-- TULISAN KOP -->
            <div class="kop-text">
                <h3>PEMERINTAH KABUPATEN SLEMAN</h3>
                <h2>DINAS KEBUDAYAAN (KUNDHA KABUDAYAN)</h2>
                <p>Jl. KRT. Pringgodiningrat No.13, Beran, Tridadi, Sleman, Yogyakarta 55511</p>
                <p>Telp. (0274) 868505 • Email: kundhakabudayan@slemankab.go.id</p>
            </div>

            <!-- LOGO KANAN (KOSONG) -->
            <div class="placeholder"></div>

        </div>
    </div>

    <h2 class="judul">LAPORAN SISTEM INFORMASI MANAJEMEN ANGGARAN BBM</h2>
    <p class="subjudul">Dinas Kebudayaan (Kundha Kabudayan) Kabupaten Sleman</p>

    <!-- ====================== TABEL DATA ======================= -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Plat</th>
                <th>Divisi</th>
                <th>Penanggung Jawab</th>
                <th>Anggaran Total (Rp)</th>
                <th>Pemakaian (Rp)</th>
                <th>Sisa (Rp)</th>
            </tr>
        </thead>
        <tbody>

            @forelse($anggaran as $index => $a)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $a->kendaraan->no_plat ?? '-' }}</td>
                <td>{{ $a->kendaraan->penanggungJawab->divisi->nama_divisi ?? '-' }}</td>
                <td>{{ $a->kendaraan->penanggungJawab->nama_pj ?? '-' }}</td>
                <td>Rp {{ number_format($a->anggaran_total, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($a->pemakaian, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($a->sisa, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Tidak ada data anggaran.</td>
            </tr>
            @endforelse

        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak pada: {{ $tanggalCetak }}
    </div>

    <!-- TTD -->
    <div class="ttd">
        <p>Sleman, {{ $tanggalCetak }}</p>
        <p><strong>Kepala Dinas Kebudayaan</strong></p>

        <br><br><br><br>

        <p><u>__________________________</u></p>
        <p>NIP. ______________________</p>
    </div>

    <div class="clear"></div>

</body>
</html>
