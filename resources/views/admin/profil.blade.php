@extends('layouts.main')

@section('content')

<style>
/* === PROFIL ADMIN (Modern Elegant Style) === */
.profile-container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #eef2ff, #ffffff);
    min-height: 85vh;
}

/* Kartu kiri */
.profile-sidebar {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    width: 300px;
    padding: 2rem 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}
.profile-sidebar:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.profile-sidebar img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid #3b82f6;
    margin-bottom: 1rem;
    background: #f3f4f6;
    object-fit: cover;
    aspect-ratio: 1 / 1;
}


.profile-sidebar h5 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e3a8a;
    margin-bottom: 0.3rem;
}

.profile-role {
    background: #e0e7ff;
    color: #1e3a8a;
    font-size: 0.85rem;
    padding: 4px 10px;
    border-radius: 8px;
    display: inline-block;
    margin-bottom: 1rem;
}

.profile-info {
    text-align: left;
    font-size: 0.95rem;
    color: #374151;
}

.profile-info p {
    margin: 0.5rem 0;
}
.profile-info i {
    color: #2563eb;
    margin-right: 6px;
}

/* Kartu kanan */
.profile-main {
    flex: 1;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    padding: 2rem;
    transition: all 0.3s ease;
}
.profile-main:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.7rem;
}

.profile-header h5 {
    font-size: 1.15rem;
    font-weight: 600;
    color: #1e3a8a;
    display: flex;
    align-items: center;
    gap: 6px;
}

.profile-detail {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem 1.2rem;
    font-size: 0.95rem;
    color: #1f2937;
}

/* Tombol */
.btn-primary {
    background: #2563eb;
    border: none;
    padding: 0.45rem 0.9rem;
    border-radius: 8px;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 500;
    transition: background 0.2s ease;
}
.btn-primary:hover {
    background: #1e40af;
}
.btn-secondary {
    background: #e5e7eb;
    border: none;
    padding: 0.45rem 0.9rem;
    border-radius: 8px;
    color: #111827;
    font-size: 0.85rem;
    font-weight: 500;
    transition: background 0.2s ease;
}
.btn-secondary:hover {
    background: #d1d5db;
}

/* Responsif */
@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
        align-items: center;
        padding: 1.5rem;
    }
    .profile-sidebar,
    .profile-main {
        width: 100%;
        max-width: 600px;
    }
}
</style>

<div class="profile-container">
    <!-- KIRI -->
    <div class="profile-sidebar">
        <img src="{{ $user->avatar ? asset($user->avatar) : asset('images/admin-avatar.png') }}" alt="Avatar">
        <h5>{{ $user->name }}</h5>
        <div class="profile-role">Super Admin</div>

        <div class="profile-info">
            <p><i class="fas fa-envelope"></i>{{ $user->email }}</p>
            <p><i class="fas fa-building"></i>Dinas Kebudayaan Sleman</p>
            <p><i class="fas fa-map-marker-alt"></i>Sleman, Yogyakarta</p>
        </div>
    </div>

    <!-- KANAN -->
    <div class="profile-main">
        <div class="profile-header">
            <h5><i class="fas fa-id-card-alt"></i> Profile Created</h5>
            <div class="profile-actions">
                <a href="{{ route('admin.pengaturan') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="profile-detail">
            <p><i class="far fa-calendar-alt text-blue-500"></i> {{ $user->created_at->format('d M Y') }}</p>
        </div>
    </div>
</div>

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

@endsection
