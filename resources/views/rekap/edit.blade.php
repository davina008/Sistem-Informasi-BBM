@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-center">Edit Data Rekap</h3>

    <form action="{{ route('rekap.update', $rekap->id_rekap) }}" method="POST" id="formRekap" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- ID Rekap -->
        <div class="mb-3">
            <label class="form-label">ID Rekap</label>
            <input type="text" class="form-control" name="id_rekap" value="{{ $rekap->id_rekap }}" readonly>
        </div>

        <!-- Tanggal -->
        <div class="mb-3">
            <label for="tgl" class="form-label">Tanggal</label>
            <input type="date" id="tgl" name="tgl" class="form-control" value="{{ $rekap->tgl }}" required>
        </div>

        <!-- Pengeluaran -->
        <div class="mb-3">
            <label for="pengeluaran" class="form-label">Pengeluaran (Rp)</label>
            <input type="text" id="pengeluaran" name="pengeluaran" class="form-control rupiah"
                   value="{{ number_format($rekap->pengeluaran, 0, ',', '.') }}" placeholder="Masukkan pengeluaran" required>
        </div>

        <!-- ID Anggaran -->
        <div class="mb-3">
            <label for="id_anggaran" class="form-label">ID Anggaran</label>
            <select id="id_anggaran" name="id_anggaran" class="form-select" required>
                <option value="" disabled>-- Pilih ID Anggaran --</option>
                @foreach($anggaran as $a)
                    <option value="{{ $a->id_anggaran }}"
                        {{ $rekap->id_anggaran == $a->id_anggaran ? 'selected' : '' }}>
                        {{ $a->id_anggaran }} - {{ $a->kendaraan->no_plat ?? 'Tidak Ada Plat' }} - Tahun {{ $a->tahun }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Upload Bukti Struk -->
        <div class="mb-3">
            <label for="bukti_struk" class="form-label">Upload Bukti Struk</label>
            <input type="file" class="form-control" id="bukti_struk" name="bukti_struk" accept=".jpg,.jpeg,.png">
            <small class="text-muted">Format: JPG, PNG | Maksimal 2MB</small>

            @if($rekap->bukti_struk)
                <div class="mt-2">
                    <p>File saat ini:
                        <a href="{{ asset('storage/bukti_struk/' . $rekap->bukti_struk) }}" target="_blank">
                            {{ $rekap->bukti_struk }}
                        </a>
                    </p>
                    <img src="{{ asset('storage/bukti_struk/' . $rekap->bukti_struk) }}" 
                         alt="Bukti Struk" width="150" class="img-thumbnail mt-2">
                </div>
            @endif
        </div>

        <!-- Tombol -->
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('rekap.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- Format Rupiah -->
<script>
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

    document.querySelectorAll('.rupiah').forEach(function(input) {
        input.addEventListener('keyup', function() {
            this.value = formatRupiah(this.value);
        });
    });

    document.getElementById('formRekap').addEventListener('submit', function() {
        const input = document.getElementById('pengeluaran');
        input.value = input.value.replace(/\./g, '');
    });
</script>
@endsection
