@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-center">Tambah Data Anggaran</h3>

    <form action="{{ route('anggaran.store') }}" method="POST" id="formAnggaran">
        @csrf

        {{-- ================= ID ANGARAN (PREVIEW) ================= --}}
        <div class="mb-3">
            <label class="form-label">ID Anggaran</label>
            <input type="text"
                   id="preview_id_anggaran"
                   class="form-control"
                   value="Pilih kendaraan & tahun"
                   readonly>
            <small class="text-muted">ID dibuat otomatis oleh sistem</small>
        </div>

        {{-- ID ANGARAN (HIDDEN) --}}
        <input type="hidden" name="id_anggaran" id="id_anggaran">

        {{-- ================= KENDARAAN ================= --}}
        <div class="mb-3">
            <label class="form-label">Kendaraan</label>
            <select name="id_kendaraan" id="id_kendaraan" class="form-select" required>
                <option value="">-- Pilih Kendaraan --</option>
                @foreach($kendaraan as $k)
                    <option value="{{ $k->id_kendaraan }}">
                        {{ $k->id_kendaraan }} - {{ $k->no_plat }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- ================= TAHUN ================= --}}
        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="number"
                   name="tahun"
                   id="tahun"
                   class="form-control"
                   placeholder="Contoh: 2025"
                   min="2000"
                   max="2100"
                   required>
        </div>

        {{-- ================= ANGGARAN TOTAL ================= --}}
        <div class="mb-3">
            <label class="form-label">Anggaran Total (Rp)</label>
            <input type="text"
                   name="anggaran_total"
                   id="anggaran_total"
                   class="form-control rupiah"
                   required>
        </div>

        {{-- ================= PEMAKAIAN ================= --}}
        <div class="mb-3">
            <label class="form-label">Pemakaian (Rp)</label>
            <input type="text"
                   name="pemakaian"
                   id="pemakaian"
                   class="form-control rupiah"
                   value="0">
        </div>

        {{-- ================= SISA ================= --}}
        <div class="mb-4">
            <label class="form-label">Sisa (Rp)</label>
            <input type="text"
                   id="sisa"
                   class="form-control rupiah"
                   readonly>
        </div>

        {{-- ================= BUTTON ================= --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('anggaran.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
/* ========== FORMAT RUPIAH ========== */
function formatRupiah(angka) {
    let number = angka.replace(/[^0-9]/g, '');
    return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.querySelectorAll('.rupiah').forEach(input => {
    input.addEventListener('input', function () {
        this.value = formatRupiah(this.value);
    });
});

/* ========== HITUNG SISA ========== */
const totalInput = document.getElementById('anggaran_total');
const pakaiInput = document.getElementById('pemakaian');
const sisaInput  = document.getElementById('sisa');

function hitungSisa() {
    const total = parseInt(totalInput.value.replace(/\./g, '')) || 0;
    const pakai = parseInt(pakaiInput.value.replace(/\./g, '')) || 0;
    sisaInput.value = formatRupiah(String(total - pakai));
}

totalInput.addEventListener('input', hitungSisa);
pakaiInput.addEventListener('input', hitungSisa);

/* ========== GENERATE ID ANGARAN ========== */
const kendaraanSelect = document.getElementById('id_kendaraan');
const tahunInput     = document.getElementById('tahun');
const previewId      = document.getElementById('preview_id_anggaran');
const hiddenId       = document.getElementById('id_anggaran');

async function generateIdAnggaran() {
    const kendaraan = kendaraanSelect.value;
    const tahun     = tahunInput.value;

    if (!kendaraan || !tahun) {
        previewId.value = 'Pilih kendaraan & tahun';
        hiddenId.value  = '';
        return;
    }

    try {
        const res = await fetch(
            "{{ route('anggaran.generate-id') }}" +
            `?id_kendaraan=${kendaraan}&tahun=${tahun}`
        );

        const data = await res.json();

        if (data.id_anggaran) {
            previewId.value = data.id_anggaran;
            hiddenId.value  = data.id_anggaran;
        } else {
            previewId.value = 'Gagal generate ID';
            hiddenId.value  = '';
        }
    } catch (err) {
        previewId.value = 'Gagal generate ID';
        hiddenId.value  = '';
    }
}

kendaraanSelect.addEventListener('change', generateIdAnggaran);
tahunInput.addEventListener('input', generateIdAnggaran);

/* ========== BEFORE SUBMIT (BERSIHKAN TITIK) ========== */
document.getElementById('formAnggaran').addEventListener('submit', function () {
    totalInput.value = totalInput.value.replace(/\./g, '');
    pakaiInput.value = pakaiInput.value.replace(/\./g, '');
});
</script>
@endsection
