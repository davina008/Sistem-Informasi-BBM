<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 📦 Import Controller
use App\Http\Controllers\{
    DashboardController,
    DivisiController,
    PenanggungJawabController,
    KendaraanController,
    AnggaranController,
    RekapController,
    AuthController,
    AdminController,
    LaporanController,
    WaController
};

/*
|--------------------------------------------------------------------------
| 🔐 Login & Register
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| 🔐 Semua route berikut wajib login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 🏠 Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | 🚗 KENDARAAN - AJAX GENERATE ID
    | ⚠️ HARUS DI ATAS resource
    |--------------------------------------------------------------------------
    */
    Route::get('/kendaraan/generate-id', [KendaraanController::class, 'generateId'])
        ->name('kendaraan.generate-id');

    /*
    |--------------------------------------------------------------------------
    | 💰 ANGGARAN - AJAX GENERATE ID
    | ⚠️ HARUS DI ATAS resource
    |--------------------------------------------------------------------------
    */
    Route::get('/anggaran/generate-id', [AnggaranController::class, 'generateId'])
        ->name('anggaran.generate-id');

    /*
    |--------------------------------------------------------------------------
    | 💰 ANGGARAN - KIRIM NOTIFIKASI
    | ⚠️ HARUS DI ATAS resource
    |--------------------------------------------------------------------------
    */
    Route::post('/anggaran/{id_anggaran}/kirim-notif',
        [AnggaranController::class, 'kirimNotif']
    )->name('anggaran.kirimNotif');

    /*
    |--------------------------------------------------------------------------
    | 📋 MASTER DATA (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::resources([
        'divisi'           => DivisiController::class,
        'penanggung-jawab' => PenanggungJawabController::class,
        'kendaraan'        => KendaraanController::class,
        'anggaran'         => AnggaranController::class,
        'rekap'            => RekapController::class,
    ]);

    /*
    |--------------------------------------------------------------------------
    | 📤 EXPORT PDF
    |--------------------------------------------------------------------------
    */
    Route::get('/laporan/semua-data/pdf', [LaporanController::class, 'exportSemuaDataPDF'])
        ->name('laporan.export.pdf');

    Route::get('/anggaran/export/pdf', [AnggaranController::class, 'exportPDF'])
        ->name('anggaran.export.pdf');

    Route::get('/rekap/export/pdf', [RekapController::class, 'exportPDF'])
        ->name('rekap.export.pdf');

    /*
    |--------------------------------------------------------------------------
    | 📱 WhatsApp
    |--------------------------------------------------------------------------
    */
    Route::post('/kirim-whatsapp', [WaController::class, 'send'])
        ->name('whatsapp.kirim');

    Route::post('/send-wa', [WaController::class, 'send'])
        ->name('send.wa');

    /*
    |--------------------------------------------------------------------------
    | 👤 Admin
    |--------------------------------------------------------------------------
    */
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/dashboard', 'dashboard')->name('admin.dashboard');
        Route::get('/admin/profil', 'profil')->name('admin.profil');
        Route::get('/admin/pengaturan', 'pengaturan')->name('admin.pengaturan');
        Route::post('/admin/pengaturan/update', 'updatePengaturan')
            ->name('admin.pengaturan.update');
    });

    /*
    |--------------------------------------------------------------------------
    | 🧑‍💼 Super Admin
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:isSuperAdmin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index'])
            ->name('admin.users.index');

        Route::post('/admin/users/{id}/approve', [AdminController::class, 'approve'])
            ->name('admin.users.approve');

        Route::post('/admin/users/{id}/reject', [AdminController::class, 'reject'])
            ->name('admin.users.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | 🚪 Logout
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| 🏁 Redirect "/" → Login / Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
