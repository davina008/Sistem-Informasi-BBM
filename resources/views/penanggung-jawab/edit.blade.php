@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h4 class="mb-0 text-center">Edit Data Penanggung Jawab</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('penanggung-jawab.update', $penanggungJawab->id_penanggung_jawab) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ID Penanggung Jawab -->
                <div class="mb-3">
                    <label for="id_penanggung_jawab" class="form-label">ID Penanggung Jawab</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="id_penanggung_jawab" 
                        name="id_penanggung_jawab"
                        value="{{ $penanggungJawab->id_penanggung_jawab }}" 
                        readonly
                    >
                </div>

                <!-- Nama Penanggung Jawab -->
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Penanggung Jawab</label>
                    <input 
                        type="text" 
                        class="form-control @error('nama') is-invalid @enderror" 
                        id="nama" 
                        name="nama"
                        value="{{ old('nama', $penanggungJawab->nama) }}" 
                        required
                    >

                    <!-- 🔹 Note tambahan -->
                    <small class="text-warning d-block mt-1">
                        * Khusus kendaraan <strong>Operasional</strong>, nama Penanggung Jawab diisi <strong>“Operasional”</strong>.
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
                        class="form-control @error('no_hp') is-invalid @enderror" 
                        id="no_hp" 
                        name="no_hp"
                        value="{{ old('no_hp', $penanggungJawab->no_hp) }}" 
                        required
                    >
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Divisi -->
                <div class="mb-4">
                    <label for="id_divisi" class="form-label">Pilih Divisi</label>
                    <select 
                        name="id_divisi" 
                        id="id_divisi" 
                        class="form-select @error('id_divisi') is-invalid @enderror" 
                        required
                    >
                        <option value="" disabled>-- Pilih Divisi --</option>
                        @foreach($divisis as $divisi)
                            <option 
                                value="{{ $divisi->id_divisi }}" 
                                {{ $penanggungJawab->id_divisi == $divisi->id_divisi ? 'selected' : '' }}
                            >
                                {{ $divisi->id_divisi }} - {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_divisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('penanggung-jawab.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
