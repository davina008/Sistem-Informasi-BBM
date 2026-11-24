@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Tambah Data Divisi</h3>

    <form action="{{ route('divisi.store') }}" method="POST">
        @csrf

        <!-- ID Divisi (Otomatis) -->
        <div class="mb-3">
            <label for="id_divisi" class="form-label">ID Divisi</label>
            <input
                type="text"
                id="id_divisi"
                name="id_divisi" {{-- 🔥 WAJIB ADA --}}
                class="form-control"
                value="{{ $newId }}"
                readonly
            >
            <small class="text-muted">
                ID dibuat otomatis oleh sistem
            </small>
        </div>

        <!-- Nama Divisi -->
        <div class="mb-4">
            <label for="nama_divisi" class="form-label">Nama Divisi</label>
            <input
                type="text"
                id="nama_divisi"
                name="nama_divisi"
                class="form-control @error('nama_divisi') is-invalid @enderror"
                placeholder="Masukkan nama divisi"
                value="{{ old('nama_divisi') }}"
                required
            >

            @error('nama_divisi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('divisi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
