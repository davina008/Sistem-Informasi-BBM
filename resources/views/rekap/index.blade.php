@extends('layouts.main')

@section('title', 'Data Rekap')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4 fw-bold text-primary">Data Rekap</h4>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            {{-- 🔹 FILTER --}}
            <form action="{{ route('rekap.index') }}" method="GET" class="row g-2 align-items-center mb-4">
                <div class="col-auto fw-semibold text-secondary">Filter Periode:</div>

                <div class="col-auto">
                    <select name="bulan" class="form-select">
                        <option value="">Pilih Bulan</option>
                        @foreach ([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $name)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <select name="tahun" class="form-select">
                        <option value="">Pilih Tahun</option>
                        @for ($year = 2020; $year <= 2035; $year++)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-auto">
                    <input type="text" name="plat" class="form-control"
                           placeholder="Plat kendaraan"
                           value="{{ request('plat') }}">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('rekap.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </a>
                </div>

                <div class="col-auto ms-auto">
                    <a href="{{ route('rekap.export.pdf', request()->all()) }}"
                       class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                </div>
            </form>

            {{-- 🔍 SEARCH & TAMBAH --}}
            <div class="row mb-3 g-2 align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('rekap.index') }}" method="GET">
                        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                        <input type="hidden" name="plat" value="{{ request('plat') }}">

                        <div class="input-group">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari ID / tanggal / plat"
                                   value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                <div class="col-md-4 text-md-end">
                    <a href="{{ route('rekap.create') }}" class="btn btn-success shadow-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Data
                    </a>
                </div>
            </div>

            {{-- 📊 TABEL --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>ID Rekap</th>
                            <th>ID Anggaran</th>
                            <th>No Plat</th>
                            <th>Tanggal</th>
                            <th>Pengeluaran</th>
                            <th>Bukti</th>
                            <th width="18%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekaps as $rekap)
                        <tr>
                            <td class="text-center fw-semibold">{{ $rekap->id_rekap }}</td>
                            <td class="text-center">{{ $rekap->id_anggaran }}</td>
                            <td class="text-center">
                                {{ optional(optional($rekap->anggaran)->kendaraan)->no_plat ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($rekap->tgl)->format('d/m/Y') }}
                            </td>
                            <td class="text-end fw-bold text-danger">
                                Rp {{ number_format($rekap->pengeluaran, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if ($rekap->bukti_struk)
                                    <a href="{{ asset('storage/'.$rekap->bukti_struk) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$rekap->bukti_struk) }}"
                                             width="70" height="70"
                                             class="rounded border"
                                             style="object-fit:cover;">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('rekap.edit', $rekap->id_rekap) }}"
                                       class="btn btn-warning btn-sm shadow-sm">
                                        <i class="fas fa-edit text-white"></i>
                                    </a>

                                    <form id="delete-form-{{ $rekap->id_rekap }}"
                                          action="{{ route('rekap.destroy', $rekap->id_rekap) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $rekap->id_rekap }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Tidak ada data rekap
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ NOTIFIKASI BERHASIL (KECIL / TOAST) --}}
@if (session('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    width: '320px'
});
</script>
@endif

<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endpush
