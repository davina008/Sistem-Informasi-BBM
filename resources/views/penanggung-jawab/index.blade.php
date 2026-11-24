@extends('layouts.main')

@section('title', 'Data Penanggung Jawab')

@section('content')
<div class="container-fluid">

    <!-- 🔹 Judul Halaman -->
    <h4 class="mb-4 fw-bold text-primary">Data Penanggung Jawab</h4>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <!-- 🔍 Form Pencarian & Tombol Tambah -->
            <div class="row mb-3 g-2 align-items-center">
                <div class="col-md-8 col-12">
                    <form action="{{ route('penanggung-jawab.index') }}" method="GET">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary" title="Cari Data">
                                <i class="fas fa-search"></i>
                            </button>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari nama atau nomor HP..."
                                   value="{{ request('search') }}">
                            <a href="{{ route('penanggung-jawab.index') }}"
                               class="btn btn-secondary" title="Refresh">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </a>
                        </div>
                    </form>
                </div>

                <div class="col-md-4 col-12 text-md-end text-start">
                    <a href="{{ route('penanggung-jawab.create') }}"
                       class="btn btn-success shadow-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Data
                    </a>
                </div>
            </div>

            <!-- 📋 Tabel Data -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th width="10%">ID PJ</th>
                            <th>Nama</th>
                            <th>No HP</th>
                            <th>Divisi</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($penanggungJawabs as $pj)
                        @php
                            $cleanNumber = preg_replace('/[^0-9]/', '', $pj->no_hp);
                            if (\Illuminate\Support\Str::startsWith($cleanNumber, '0')) {
                                $cleanNumber = '62' . substr($cleanNumber, 1);
                            }
                        @endphp

                        <tr>
                            <td class="text-center fw-semibold">
                                {{ $pj->id_penanggung_jawab }}
                            </td>
                            <td>{{ $pj->nama }}</td>
                            <td>{{ $pj->no_hp }}</td>
                            <td>{{ $pj->divisi->nama_divisi ?? '-' }}</td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center"
                                     style="gap:10px;">

                                    <!-- ✏️ Edit (TIDAK DIUBAH) -->
                                    <a href="{{ route('penanggung-jawab.edit', $pj->id_penanggung_jawab) }}"
                                       class="btn btn-warning btn-sm rounded-2 shadow-sm d-flex align-items-center justify-content-center"
                                       style="width:36px; height:36px;"
                                       title="Edit Data">
                                        <i class="fas fa-edit text-white"></i>
                                    </a>

                                    <!-- 🗑️ Hapus  -->
                                    <form id="delete-form-{{ $pj->id_penanggung_jawab }}"
                                          action="{{ route('penanggung-jawab.destroy', $pj->id_penanggung_jawab) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="btn btn-danger btn-sm rounded-2 shadow-sm d-flex align-items-center justify-content-center btn-delete"
                                                data-id="{{ $pj->id_penanggung_jawab }}"
                                                style="width:36px; height:36px;"
                                                title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Tidak ada data penanggung jawab
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            <!-- 📄 Pagination -->
            @if ($penanggungJawabs->count())
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                    <div class="small text-secondary mb-2">
                        Menampilkan
                        <strong>{{ $penanggungJawabs->firstItem() }}</strong> -
                        <strong>{{ $penanggungJawabs->lastItem() }}</strong>
                        dari <strong>{{ $penanggungJawabs->total() }}</strong> data
                    </div>
                    <div>
                        {!! $penanggungJawabs->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection


{{-- ✅ SCRIPT (WAJIB PUSH, TANPA UBAH UI) --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {

            const id = this.dataset.id;
            const form = document.getElementById('delete-form-' + id);

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            toast: true,
            position: 'top-end',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}"
        });
    @endif
});
</script>
@endpush
