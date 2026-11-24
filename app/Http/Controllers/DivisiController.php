<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Divisi;
use Illuminate\Support\Facades\Log;

class DivisiController extends Controller
{
    /**
     * 📋 Tampilkan daftar divisi
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $divisis = Divisi::when($search, function ($query, $search) {
                return $query->where('nama_divisi', 'like', "%{$search}%");
            })
            ->orderBy('id_divisi', 'asc')
            ->paginate(8);

        return view('divisi.index', compact('divisis'));
    }

    /**
     * 🆕 Form tambah data divisi
     * ➜ ID otomatis ditampilkan di form
     */
    public function create()
    {
        $last = Divisi::orderBy('id_divisi', 'desc')->first();

        $newId = $last
            ? 'DIV' . str_pad(((int) substr($last->id_divisi, 3)) + 1, 3, '0', STR_PAD_LEFT)
            : 'DIV001';

        return view('divisi.create', compact('newId'));
    }

    /**
     * 💾 Simpan data divisi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_divisi'   => 'required|string|max:10|unique:divisi,id_divisi',
            'nama_divisi' => 'required|string|max:100'
        ]);

        try {
            Divisi::create([
                'id_divisi'   => $request->id_divisi,
                'nama_divisi' => $request->nama_divisi
            ]);

            return redirect()->route('divisi.index')
                ->with('success', 'Data divisi berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Gagal menambahkan divisi: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambah data.');
        }
    }

    /**
     * ✏️ Form edit data divisi
     */
    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('divisi.edit', compact('divisi'));
    }

    /**
     * 🔄 Update data divisi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:100'
        ]);

        try {
            $divisi = Divisi::findOrFail($id);
            $divisi->update([
                'nama_divisi' => $request->nama_divisi
            ]);

            return redirect()->route('divisi.index')
                ->with('success', 'Data divisi berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal update divisi: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * 🗑️ Hapus data divisi
     */
    public function destroy($id)
    {
        try {
            $divisi = Divisi::findOrFail($id);
            $divisi->delete();

            return redirect()->route('divisi.index')
                ->with('success', 'Data divisi berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal hapus divisi: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
