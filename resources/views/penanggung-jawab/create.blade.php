@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-center">Tambah Data Penanggung Jawab</h3>

    <form action="{{ route('penanggung-jawab.store') }}" method="POST">
        @csrf

        <!-- ID Penanggung Jawab (TAMPILAN) -->
        <div class="mb-3">
            <label class="form-label">ID Penanggung Jawab</label>
            <input
                type="text"
                class="form-control"
                value="{{ $newId }}"
                readonly
            >
            <small class="text-muted">
                ID dibuat otomatis oleh sistem
            </small>
        </div>

        <!-- ID Penanggung Jawab (DIKIRIM KE CONTROLLER) -->
        <input type="hidden" name="id_penanggung_jawab" value="{{ $newId }}">

        <!-- Nama Penanggung Jawab -->
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Penanggung Jawab</label>
            <input
                type="text"
                id="nama"
                name="nama"
                class="form-control @error('nama') is-invalid @enderror"
                placeholder="Masukkan nama lengkap"
                value="{{ old('nama') }}"
                required
            >

            <small class="text-warning d-block mt-1">
                * Khusus kendaraan <strong>Operasional</strong>, isi dengan <strong>“Operasional”</strong>.
            </small>

            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nomor HP -->
        <div class="mb-3">
            <label for="no_hp" class="form-label">Nomor HP</label>
            <input
                type="text"
                id="no_hp"
                name="no_hp"
                class="form-control @error('no_hp') is-invalid @enderror"
                placeholder="Contoh: 081234567890"
                value="{{ old('no_hp') }}"
                required
            >
            @error('no_hp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Pilih Divisi -->
        <div class="mb-4">
            <label for="id_divisi" class="form-label">Pilih Divisi</label>
            <select
                name="id_divisi"
                id="id_divisi"
                class="form-select @error('id_divisi') is-invalid @enderror"
                required
            >
                <option value="" disabled selected>-- Pilih Divisi --</option>
                @foreach($divisis as $divisi)
                    <option
                        value="{{ $divisi->id_divisi }}"
                        {{ old('id_divisi') == $divisi->id_divisi ? 'selected' : '' }}>
                        {{ $divisi->id_divisi }} - {{ $divisi->nama_divisi }}
                    </option>
                @endforeach
            </select>
            @error('id_divisi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('penanggung-jawab.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
