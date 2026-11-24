@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-center">Tambah Data Kendaraan</h3>

    <form action="{{ route('kendaraan.store') }}" method="POST">
        @csrf

        {{-- KATEGORI --}}
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori Kendaraan</label>
            <select id="kategori" name="kategori" class="form-select" required>
                <option value="" disabled selected>-- Pilih Kategori Kendaraan --</option>
                <option value="per_plat">Per Plat</option>
                <option value="operasional">Operasional</option>
            </select>
        </div>

        {{-- ❌ ID KENDARAAN DIHAPUS DARI TAMPILAN --}}

        {{-- NO PLAT --}}
        <div class="mb-3" id="field-no-plat">
            <label for="no_plat" class="form-label">No Plat</label>
            <input type="text"
                   id="no_plat"
                   name="no_plat"
                   class="form-control"
                   placeholder="Contoh: AB 1234 CD">
        </div>

        {{-- MEREK --}}
        <div class="mb-3">
            <label for="merek" class="form-label">Merek</label>
            <input type="text" id="merek" name="merek" class="form-control" required>
        </div>

        {{-- JENIS --}}
        <div class="mb-3">
            <label for="jenis_kendaraan" class="form-label">Jenis Kendaraan</label>
            <input type="text" id="jenis_kendaraan" name="jenis_kendaraan" class="form-control" required>
        </div>

        {{-- WARNA --}}
        <div class="mb-3">
            <label for="warna" class="form-label">Warna</label>
            <input type="text" id="warna" name="warna" class="form-control" required>
        </div>

        {{-- BBM --}}
        <div class="mb-3">
            <label for="jenis_bahan_bakar" class="form-label">Jenis Bahan Bakar</label>
            <select id="jenis_bahan_bakar" name="jenis_bahan_bakar" class="form-select" required>
                <option value="" disabled selected>-- Pilih Jenis Bahan Bakar --</option>
                <option value="Pertalite">Pertalite</option>
                <option value="Pertamax">Pertamax</option>
                <option value="Pertamax Turbo">Pertamax Turbo</option>
                <option value="Pertamina Dex">Pertamina Dex</option>
                <option value="Dexlite">Dexlite</option>
                <option value="Bio Solar">Bio Solar</option>
                <option value="Semua Jenis BBM">Semua Jenis BBM</option>
            </select>
        </div>

        {{-- PENANGGUNG JAWAB --}}
        <div class="mb-4">
            <label for="id_penanggung_jawab" class="form-label">Penanggung Jawab</label>
            <select id="id_penanggung_jawab" name="id_penanggung_jawab" class="form-select" required>
                <option value="" disabled selected>-- Pilih Penanggung Jawab --</option>
                @foreach($penanggungJawab as $pj)
                    <option value="{{ $pj->id_penanggung_jawab }}">
                        {{ $pj->id_penanggung_jawab }} - {{ $pj->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- TOMBOL --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategori = document.getElementById('kategori');
    const platField = document.getElementById('field-no-plat');
    const noPlat = document.getElementById('no_plat');

    kategori.addEventListener('change', function () {
        if (!this.value) return;

        // hanya atur tampilan (tanpa generate ID lagi)
        if (this.value === 'operasional') {
            platField.style.display = 'none';
            noPlat.value = '';
            noPlat.removeAttribute('required');
        } else {
            platField.style.display = 'block';
            noPlat.setAttribute('required', 'required');
        }
    });
});
</script>
@endsection