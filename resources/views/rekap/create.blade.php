@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-center">Tambah Data Rekap</h3>

    <form action="{{ route('rekap.store') }}" method="POST" id="formRekap" enctype="multipart/form-data">
        @csrf

        <!-- ID Rekap -->
        <div class="mb-3">
            <label class="form-label fw-semibold">ID Rekap</label>
            <input type="text" class="form-control" name="id_rekap" value="{{ $newId }}" readonly>
        </div>

        <!-- Tanggal -->
        <div class="mb-3">
            <label for="tgl" class="form-label fw-semibold">Tanggal</label>
            <input type="date" id="tgl" name="tgl" class="form-control" required>
        </div>

        <!-- Pengeluaran -->
        <div class="mb-3">
            <label for="pengeluaran" class="form-label fw-semibold">Pengeluaran (Rp)</label>
            <input type="text" id="pengeluaran" name="pengeluaran" class="form-control rupiah" placeholder="Masukkan pengeluaran" required>
        </div>

        <!-- ID Anggaran -->
        <div class="mb-3">
            <label for="id_anggaran" class="form-label fw-semibold">ID Anggaran</label>
            <select id="id_anggaran" name="id_anggaran" class="form-select" required>
                <option value="" disabled selected>-- Pilih ID Anggaran --</option>
                @foreach($anggaran as $a)
                    <option value="{{ $a->id_anggaran }}">
                        {{ $a->id_anggaran }} - {{ $a->kendaraan->no_plat ?? 'Tanpa Plat' }} - Tahun {{ $a->tahun }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Upload Bukti Struk -->
        <div class="mb-3">
            <label for="bukti_struk" class="form-label fw-semibold">Upload Bukti Struk <span class="text-danger">*</span></label>
            <input 
                type="file" 
                class="form-control @error('bukti_struk') is-invalid @enderror" 
                name="bukti_struk" 
                id="bukti_struk" 
                accept="image/*"
                required
            >
            <small class="text-muted d-block mt-1">Format: JPG, PNG | Maksimal 2MB</small>

            <!-- Preview Gambar -->
            <div class="mt-3">
                <img id="preview" src="#" alt="Preview Struk" style="display:none; max-width: 250px; border-radius: 10px; border: 1px solid #ccc;">
            </div>

            @error('bukti_struk')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('rekap.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- Script Format Rupiah & Preview Gambar -->
<script>
    // 💰 Format angka ke Rupiah
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // 🔄 Format input Rupiah otomatis saat mengetik
    document.querySelectorAll('.rupiah').forEach(function(input) {
        input.addEventListener('keyup', function() {
            this.value = formatRupiah(this.value);
        });
    });

    // ⏩ Hapus titik sebelum dikirim ke server
    document.getElementById('formRekap').addEventListener('submit', function() {
        const input = document.getElementById('pengeluaran');
        input.value = input.value.replace(/\./g, '');
    });

    // 🖼️ Preview Gambar Otomatis
    document.getElementById('bukti_struk').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');

        if (file) {
            const fileSize = file.size / 1024 / 1024; // ukuran dalam MB
            if (fileSize > 2) {
                alert("Ukuran file maksimal 2MB!");
                event.target.value = ""; // reset input
                preview.style.display = 'none';
                preview.src = "#";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.display = 'block';
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            preview.src = '#';
        }
    });
</script>
@endsection
