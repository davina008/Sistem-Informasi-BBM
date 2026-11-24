@extends('layouts.main')

@section('title', 'Data Anggaran')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4 fw-bold text-primary">Data Anggaran</h4>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            {{-- Filter --}}
            <form action="{{ route('anggaran.index') }}" method="GET" class="row g-2 mb-4">
                <div class="col-auto fw-semibold">Filter Periode:</div>

                <div class="col-auto">
                    <select name="bulan" class="form-select">
                        <option value="">Bulan</option>
                        @foreach ([1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'] as $k=>$v)
                            <option value="{{ $k }}" {{ request('bulan')==$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <select name="tahun" class="form-select">
                        <option value="">Tahun</option>
                        @for ($y=2020;$y<=date('Y')+10;$y++)
                            <option value="{{ $y }}" {{ request('tahun')==$y?'selected':'' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-auto">
                    <button class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('anggaran.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            {{-- Search & Tambah --}}
            <div class="row mb-3">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('anggaran.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari anggaran..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('anggaran.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>ID Kendaraan</th>
                            <th>No Plat</th>
                            <th>ID Anggaran</th>
                            <th>Tahun</th>
                            <th>Anggaran</th>
                            <th>Pemakaian</th>
                            <th>Sisa</th>
                            <th width="18%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggarans as $a)
                        <tr>
                            <td class="text-center">{{ $a->id_kendaraan }}</td>
                            <td class="text-center">{{ $a->kendaraan->no_plat ?? '-' }}</td>
                            <td class="text-center">{{ $a->id_anggaran }}</td>
                            <td class="text-center">{{ $a->tahun }}</td>
                            <td class="text-end">{{ number_format($a->anggaran_total,0,',','.') }}</td>
                            <td class="text-end">{{ number_format($a->pemakaian,0,',','.') }}</td>
                            <td class="text-end text-success fw-bold">
                                {{ number_format($a->sisa,0,',','.') }}
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('anggaran.edit', $a->id_anggaran) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit text-white"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('anggaran.destroy', $a->id_anggaran) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                   
                                    <form action="{{ route('anggaran.kirimNotif', $a->id_anggaran) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-success btn-sm">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {!! $anggarans->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.onclick = function () {
        const form = this.closest('form');
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(res => {
            if (res.isConfirmed) form.submit();
        });
    }
});
</script>
@endpush
