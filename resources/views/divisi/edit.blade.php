@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Edit Data Divisi</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('divisi.update', $divisi->id_divisi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_divisi" class="form-label">ID Divisi</label>
                    <input type="text" class="form-control" id="id_divisi" name="id_divisi"
                        value="{{ $divisi->id_divisi }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="nama_divisi" class="form-label">Nama Divisi</label>
                    <input type="text" class="form-control" id="nama_divisi" name="nama_divisi"
                        value="{{ $divisi->nama_divisi }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('divisi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
