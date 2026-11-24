<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Daftar policy yang terdaftar untuk model tertentu.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Contoh:
        // 'App\Models\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Registrasi layanan autentikasi & otorisasi.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * 🛡️ Gate: Super Admin
         * Hanya user dengan role = 'super_admin' yang bisa
         * mengakses fitur manajemen user dan approval akun.
         */
        Gate::define('isSuperAdmin', function (User $user) {
            return $user->role === 'super_admin';
        });

        /**
         * 🧭 Gate: Admin
         * Termasuk admin biasa & super admin (akses penuh ke dashboard admin).
         */
        Gate::define('isAdmin', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });

        /**
         * 👤 Gate: User biasa
         * Hanya user dengan role = 'user' (misal pegawai, operator, dll).
         */
        Gate::define('isUser', function (User $user) {
            return $user->role === 'user';
        });
    }
}
