<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use App\Models\Divisi;

class PenanggungJawabController extends Controller
{
    /**
     * 📋 Tampilkan daftar Penanggung Jawab
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $penanggungJawabs = PenanggungJawab::with('divisi')
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->orderBy('id_penanggung_jawab', 'asc')
            ->paginate(8);

        return view('penanggung-jawab.index', [
            'penanggungJawabs' => $penanggungJawabs,
            'i' => (request()->input('page', 1) - 1) * 8,
        ]);
    }

    /**
     * 🆕 Form tambah data Penanggung Jawab
     * ➜ ID otomatis ditampilkan di form
     */
    public function create()
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();

        $last = PenanggungJawab::orderBy('id_penanggung_jawab', 'desc')->first();

        $newId = $last
            ? 'PJ' . str_pad(((int) substr($last->id_penanggung_jawab, 2)) + 1, 3, '0', STR_PAD_LEFT)
            : 'PJ001';

        return view('penanggung-jawab.create', compact('divisis', 'newId'));
    }

    /**
     * 💾 Simpan data Penanggung Jawab baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penanggung_jawab' => 'required|string|max:10|unique:penanggung_jawab,id_penanggung_jawab',
            
            'nama'      => 'required|string|max:100',
            'no_hp'     => 'required|string|max:20',
            'id_divisi' => 'required',
        ]);

        PenanggungJawab::create($validated);

        return redirect()->route('penanggung-jawab.index')
            ->with('success', 'Data penanggung jawab berhasil ditambahkan.');
    }

    /**
     * ✏️ Form edit data
     */
    public function edit(string $id)
    {
        $penanggungJawab = PenanggungJawab::findOrFail($id);
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('penanggung-jawab.edit', compact('penanggungJawab', 'divisis'));
    }

    /**
     * 🔄 Update data
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'no_hp'     => 'required|string|max:20',
            'id_divisi' => 'required',
        ]);

        $penanggungJawab = PenanggungJawab::findOrFail($id);
        $penanggungJawab->update($validated);

        return redirect()->route('penanggung-jawab.index')
            ->with('success', 'Data penanggung jawab berhasil diubah.');
    }

    /**
     * 🗑️ Hapus data
     */
    public function destroy(string $id)
    {
        $penanggungJawab = PenanggungJawab::findOrFail($id);
        $penanggungJawab->delete();

        return redirect()->route('penanggung-jawab.index')
            ->with('success', 'Data penanggung jawab berhasil dihapus.');
    }
}
