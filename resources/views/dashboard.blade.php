@extends('layouts.main')

@section('title', 'Dashboard - SIMABBM')

@section('content')
<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Dashboard</h1>
    </div>

    <!-- ==========================
            FORM FILTER
    =========================== -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Filter Dashboard</strong>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="row">

                    <!-- Filter Divisi -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Filter Divisi</label>
                        <select name="divisi" class="form-control">
                            <option value="all">Semua Divisi</option>

                            @foreach($divisis as $d)
                                <option value="{{ $d->id_divisi }}"
                                    {{ $filterDivisi == $d->id_divisi ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Periode -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Filter Periode</label>
                        <select name="periode" class="form-control" id="periodeSelect">
                            <option value="tahun_ini" {{ $filterPeriode == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                            <option value="bulan_ini" {{ $filterPeriode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="tahun" {{ $filterPeriode == 'tahun' ? 'selected' : '' }}>Pilih Tahun</option>
                            <option value="custom" {{ $filterPeriode == 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                        </select>
                    </div>

                    <!-- Pilih Tahun -->
                    <div class="col-md-4 mb-3 periode-tahun {{ $filterPeriode == 'tahun' ? '' : 'd-none' }}">
                        <label class="form-label">Pilih Tahun</label>
                        <select name="tahun" class="form-control">
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ $filterTahun == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Date -->
                    <div class="col-md-4 mb-3 periode-custom {{ $filterPeriode == 'custom' ? '' : 'd-none' }}">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start" class="form-control" value="{{ $startDate }}">
                    </div>

                    <div class="col-md-4 mb-3 periode-custom {{ $filterPeriode == 'custom' ? '' : 'd-none' }}">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end" class="form-control" value="{{ $endDate }}">
                    </div>

                </div>

                <button class="btn btn-primary mt-2">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
            </form>
        </div>
    </div>

    <!-- ==========================
            CARD RINGKASAN
    =========================== -->
    <div class="row">
        <x-dashboard-card title="Total Kendaraan" value="{{ $totalKendaraan }}" color="primary" />
        <x-dashboard-card title="Total Divisi" value="{{ $totalDivisi }}" color="success" />
        <x-dashboard-card title="Total Pemakaian BBM" value="Rp {{ number_format($totalPemakaian, 0, ',', '.') }}" color="warning" />
        <x-dashboard-card title="Total Anggaran" value="Rp {{ number_format($totalAnggaran, 0, ',', '.') }}" color="info" />
    </div>

    <!-- ==========================
            TABEL RINGKASAN
    =========================== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Ringkasan Anggaran per Kendaraan</h6>
            <!-- TOMBOL EXPORT PDF BAWAH SUDAH DIHAPUS -->
        </div>

        <div class="card-body">
            @if(count($anggaranPerKendaraan) > 0)

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No. Plat</th>
                                <th>Merek</th>
                                <th>Divisi</th>
                                <th>Anggaran Total</th>
                                <th>Pemakaian</th>
                                <th>Sisa</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($anggaranPerKendaraan as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item['no_plat'] }}</td>
                                    <td>{{ $item['merek'] }}</td>
                                    <td>{{ $item['nama_divisi'] }}</td>
                                    <td>Rp {{ number_format($item['anggaran_total'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['pemakaian'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['sisa'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <p class="text-center text-muted mb-0">Belum ada data anggaran kendaraan.</p>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const periodeSelect = document.getElementById('periodeSelect');

    function toggleInputs() {
        const periode = periodeSelect.value;

        // Hide semua
        document.querySelectorAll('.periode-tahun').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.periode-custom').forEach(el => el.classList.add('d-none'));

        // Show sesuai pilihan
        if (periode === 'tahun') {
            document.querySelectorAll('.periode-tahun').forEach(el => el.classList.remove('d-none'));
        }

        if (periode === 'custom') {
            document.querySelectorAll('.periode-custom').forEach(el => el.classList.remove('d-none'));
        }
    }

    toggleInputs(); // Jalankan saat halaman load
    periodeSelect.addEventListener('change', toggleInputs);

});
</script>
@endpush
