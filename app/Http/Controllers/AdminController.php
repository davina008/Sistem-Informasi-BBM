<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * 🧭 Dashboard Admin
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('admin.dashboard', compact('user'));
    }

    /**
     * 👤 Halaman profil admin
     */
    public function profil()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('admin.profil', compact('user'));
    }

    /**
     * ⚙️ Halaman pengaturan akun admin
     */
    public function pengaturan()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('admin.pengaturan', compact('user'));
    }

    /**
     * 💾 Update data akun admin
     */
    public function updatePengaturan(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // ✅ VALIDASI
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 🔹 Update data
        $user->name  = $request->name;
        $user->email = $request->email;

        // 🔹 Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 🔹 Upload avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('images/avatar');

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            // Hapus avatar lama
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $file->move($path, $filename);
            $user->avatar = 'images/avatar/' . $filename;
        }

        $user->save();

        return redirect()
            ->route('admin.pengaturan')
            ->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}