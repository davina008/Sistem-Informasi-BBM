@extends('layouts.main')

@section('title', 'Edit Data Anggaran')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Edit Data Anggaran</h4>
        </div>

        <div class="card-body">
            <form id="formEditAnggaran" action="{{ route('anggaran.update', $anggaran->id_anggaran) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ID Anggaran -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">ID Anggaran</label>
                    <input type="text" name="id_anggaran" class="form-control" 
                           value="{{ $anggaran->id_anggaran }}" readonly>
                </div>

                <!-- Kendaraan -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kendaraan</label>
                    <select name="id_kendaraan" class="form-select" required>
                        @foreach ($kendaraan as $k)
                            <option value="{{ $k->id_kendaraan }}" 
                                {{ $anggaran->id_kendaraan == $k->id_kendaraan ? 'selected' : '' }}>
                                {{ $k->id_kendaraan }} - {{ $k->no_plat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" 
                           value="{{ $anggaran->tahun }}" required>
                </div>

                <!-- Anggaran Total -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Anggaran Total (Rp)</label>
                    <input type="text" name="anggaran_total" id="anggaran_total" 
                           class="form-control format-rupiah" 
                           value="{{ number_format($anggaran->anggaran_total, 0, ',', '.') }}" required>
                </div>

                <!-- Pemakaian -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pemakaian (Rp)</label>
                    <input type="text" name="pemakaian" id="pemakaian" 
                           class="form-control format-rupiah" 
                           value="{{ number_format($anggaran->pemakaian, 0, ',', '.') }}">
                </div>

                <!-- Sisa -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sisa (Rp)</label>
                    <input type="text" name="sisa" id="sisa" 
                           class="form-control format-rupiah" 
                           value="{{ number_format($anggaran->sisa, 0, ',', '.') }}" readonly>
                </div>

                <!-- Tombol -->
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('anggaran.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formatInputs = document.querySelectorAll('.format-rupiah');
    const form = document.getElementById('formEditAnggaran');

    // ✅ Format angka otomatis jadi 23.089.850
    formatInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '');
            if (!value) {
                this.value = '';
                return;
            }
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    });

    // ✅ Hitung sisa otomatis: sisa = anggaran_total - pemakaian
    const total = document.getElementById('anggaran_total');
    const pemakaian = document.getElementById('pemakaian');
    const sisa = document.getElementById('sisa');

    function hitungSisa() {
        const totalVal = parseInt(total.value.replace(/\./g, '')) || 0;
        const pakaiVal = parseInt(pemakaian.value.replace(/\./g, '')) || 0;
        const hasil = totalVal - pakaiVal;
        sisa.value = new Intl.NumberFormat('id-ID').format(hasil);
    }

    total.addEventListener('input', hitungSisa);
    pemakaian.addEventListener('input', hitungSisa);

    // ✅ Hapus titik sebelum dikirim ke server
    form.addEventListener('submit', function () {
        formatInputs.forEach(function (input) {
            input.value = input.value.replace(/\./g, '');
        });
    });
});
</script>
@endsection
