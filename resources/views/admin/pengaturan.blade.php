@extends('layouts.main')

@section('content')

<div class="container mt-4">
    <h4 class="mb-4">Pengaturan Akun Admin</h4>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.pengaturan.update') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  autocomplete="off">
                @csrf

                {{-- FOTO PROFIL --}}
                <div class="mb-4 text-center">
                    <img src="{{ $user->avatar ? asset($user->avatar) : asset('images/admin-avatar.png') }}"
                         class="rounded-circle mb-2"
                         style="width:110px;height:110px;object-fit:cover">

                    <input type="file" name="avatar" class="form-control form-control-sm mt-2">
                </div>

                <hr>

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="fw-semibold">Nama</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $user->name) }}">
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="fw-semibold">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}">
                </div>

                <hr>

                {{-- PASSWORD --}}
                <h6 class="text-muted">Ubah Password (opsional)</h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Password Baru</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Kosongkan jika tidak diganti"
                               autocomplete="new-password">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               autocomplete="new-password">
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.profil') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
