@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Edit Data Kendaraan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kendaraan.update', $kendaraan->id_kendaraan) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Kategori Kendaraan -->
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori Kendaraan</label>
                    <select id="kategori" name="kategori" class="form-select" required>
                        <option value="per_plat" {{ $kendaraan->kategori == 'per_plat' ? 'selected' : '' }}>
                            Per Plat
                        </option>
                        <option value="operasional" {{ $kendaraan->kategori == 'operasional' ? 'selected' : '' }}>
                            Operasional
                        </option>
                    </select>
                </div>

                <!-- ID Kendaraan -->
                <div class="mb-3" id="field-id-kendaraan">
                    <label for="id_kendaraan" class="form-label">ID Kendaraan</label>
                    <input type="text" class="form-control" id="id_kendaraan" name="id_kendaraan"
                        value="{{ $kendaraan->id_kendaraan }}" readonly>
                </div>

                <!-- No Plat -->
                <div class="mb-3" id="field-no-plat">
                    <label for="no_plat" class="form-label">No Plat</label>
                    <input type="text" class="form-control" id="no_plat" name="no_plat"
                        value="{{ $kendaraan->no_plat }}">
                </div>

                <!-- Merek -->
                <div class="mb-3">
                    <label for="merek" class="form-label">Merek</label>
                    <input type="text" class="form-control" id="merek" name="merek"
                        value="{{ $kendaraan->merek }}" required>
                    <small class="text-warning d-block mt-1 note-operasional">
                        * Khusus kendaraan <strong>Operasional</strong>, kolom ini diisi dengan <strong>“Operasional”</strong>.
                    </small>
                </div>

                <!-- Jenis Kendaraan -->
                <div class="mb-3">
                    <label for="jenis_kendaraan" class="form-label">Jenis Kendaraan</label>
                    <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan"
                        value="{{ $kendaraan->jenis_kendaraan }}" required>
                    <small class="text-warning d-block mt-1 note-operasional">
                        * Khusus kendaraan <strong>Operasional</strong>, kolom ini diisi dengan <strong>“Operasional”</strong>.
                    </small>
                </div>

                <!-- Warna -->
                <div class="mb-3">
                    <label for="warna" class="form-label">Warna</label>
                    <input type="text" class="form-control" id="warna" name="warna"
                        value="{{ $kendaraan->warna }}" required>
                    <small class="text-warning d-block mt-1 note-operasional">
                        * Khusus kendaraan <strong>Operasional</strong>, kolom ini diisi dengan <strong>“Operasional”</strong>.
                    </small>
                </div>

                <!-- Jenis Bahan Bakar (DROPDOWN - FIX) -->
                <div class="mb-3">
                    <label for="jenis_bahan_bakar" class="form-label">Jenis Bahan Bakar</label>
                    <select id="jenis_bahan_bakar" name="jenis_bahan_bakar" class="form-select" required>
                        <option value="">-- Pilih Jenis Bahan Bakar --</option>

                        @php
                            $bbmList = [
                                'Pertalite',
                                'Pertamax',
                                'Pertamax Turbo',
                                'Pertamina Dex',
                                'Dexlite',
                                'Bio Solar',
                                'Semua Jenis BBM'
                            ];
                        @endphp

                        @foreach ($bbmList as $bbm)
                            <option value="{{ $bbm }}"
                                {{ old('jenis_bahan_bakar', $kendaraan->jenis_bahan_bakar) == $bbm ? 'selected' : '' }}>
                                {{ $bbm }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Penanggung Jawab -->
                <div class="mb-3">
                    <label for="id_penanggung_jawab" class="form-label">Penanggung Jawab</label>
                    <select id="id_penanggung_jawab" name="id_penanggung_jawab" class="form-select" required>
                        @foreach($penanggungJawab as $pj)
                            <option value="{{ $pj->id_penanggung_jawab }}"
                                {{ $kendaraan->id_penanggung_jawab == $pj->id_penanggung_jawab ? 'selected' : '' }}>
                                {{ $pj->id_penanggung_jawab }} - {{ $pj->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol -->
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- === SCRIPT DINAMIS KATEGORI === --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori');
    const idField = document.getElementById('field-id-kendaraan');
    const idInput = document.getElementById('id_kendaraan');
    const platField = document.getElementById('field-no-plat');
    const platInput = document.getElementById('no_plat');
    const notes = document.querySelectorAll('.note-operasional');

    function toggleFields() {
        const kategori = kategoriSelect.value;

        if (kategori === 'operasional') {
            idInput.value = 'Operasional';
            platInput.value = 'Operasional';
            idField.style.display = 'none';
            platField.style.display = 'none';
            notes.forEach(note => note.style.display = 'block');
        } else {
            idField.style.display = 'block';
            platField.style.display = 'block';
            notes.forEach(note => note.style.display = 'none');
        }
    }

    toggleFields();
    kategoriSelect.addEventListener('change', toggleFields);
});
</script>
@endsection
