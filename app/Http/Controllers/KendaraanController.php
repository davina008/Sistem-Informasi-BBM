<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\PenanggungJawab;

class KendaraanController extends Controller
{
    /* =======================
     * INDEX
     * ======================= */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $kendaraans = Kendaraan::with('penanggungJawab')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_plat', 'like', "%{$search}%")
                      ->orWhere('merek', 'like', "%{$search}%")
                      ->orWhere('jenis_kendaraan', 'like', "%{$search}%");
                });
            })
            ->orderBy('id_kendaraan', 'asc')
            ->paginate(8);

        return view('kendaraan.index', compact('kendaraans'));
    }

    /* =======================
     * CREATE (FIXED)
     * ======================= */
    public function create()
    {
        // ambil PJ yang sudah dipakai
        $pjTerpakai = Kendaraan::pluck('id_penanggung_jawab');

        // tampilkan hanya PJ yang belum punya kendaraan
        $penanggungJawab = PenanggungJawab::whereNotIn('id_penanggung_jawab', $pjTerpakai)->get();

        return view('kendaraan.create', compact('penanggungJawab'));
    }

    /* =======================
     * GENERATE ID
     * ======================= */
    public function generateId(Request $request)
    {
        $kategori = $request->kategori;

        if ($kategori === 'operasional') {
            $last = Kendaraan::where('id_kendaraan', 'like', 'OPR%')
                ->orderBy('id_kendaraan', 'desc')
                ->first();

            $number = $last ? (int) substr($last->id_kendaraan, 3) + 1 : 1;
            $id = 'OPR' . str_pad($number, 3, '0', STR_PAD_LEFT);
        } else {
            $last = Kendaraan::where('id_kendaraan', 'like', 'KDR%')
                ->orderBy('id_kendaraan', 'desc')
                ->first();

            $number = $last ? (int) substr($last->id_kendaraan, 3) + 1 : 1;
            $id = 'KDR' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        return response()->json([
            'id_kendaraan' => $id
        ]);
    }

    /* =======================
     * STORE (FIXED)
     * ======================= */
    public function store(Request $request)
    {
        $kategori = $request->kategori;

        $rules = [
            'kategori' => 'required|in:per_plat,operasional',
            'merek' => 'required|string|max:100',
            'jenis_kendaraan' => 'required|string|max:100',
            'warna' => 'required|string|max:50',
            'jenis_bahan_bakar' => 'required|string|max:50',
            // ✅ TAMBAH UNIQUE
            'id_penanggung_jawab' => 'required|exists:penanggung_jawab,id_penanggung_jawab|unique:kendaraan,id_penanggung_jawab',
        ];

        if ($kategori === 'per_plat') {
            $rules['no_plat'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        // generate ID
        if ($kategori === 'operasional') {
            $last = Kendaraan::where('id_kendaraan', 'like', 'OPR%')->orderBy('id_kendaraan', 'desc')->first();
            $number = $last ? (int) substr($last->id_kendaraan, 3) + 1 : 1;
            $idKendaraan = 'OPR' . str_pad($number, 3, '0', STR_PAD_LEFT);
            $noPlat = 'Operasional';
        } else {
            $last = Kendaraan::where('id_kendaraan', 'like', 'KDR%')->orderBy('id_kendaraan', 'desc')->first();
            $number = $last ? (int) substr($last->id_kendaraan, 3) + 1 : 1;
            $idKendaraan = 'KDR' . str_pad($number, 3, '0', STR_PAD_LEFT);
            $noPlat = $validated['no_plat'];
        }

        Kendaraan::create([
            'id_kendaraan' => $idKendaraan,
            'kategori' => $kategori,
            'no_plat' => $noPlat,
            'merek' => $validated['merek'],
            'jenis_kendaraan' => $validated['jenis_kendaraan'],
            'warna' => $validated['warna'],
            'jenis_bahan_bakar' => $validated['jenis_bahan_bakar'],
            'id_penanggung_jawab' => $validated['id_penanggung_jawab'],
        ]);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    /* =======================
     * EDIT (FIXED)
     * ======================= */
    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // ambil PJ yang sudah dipakai kecuali milik kendaraan ini
        $pjTerpakai = Kendaraan::where('id_kendaraan', '!=', $id)
            ->pluck('id_penanggung_jawab');

        $penanggungJawab = PenanggungJawab::whereNotIn('id_penanggung_jawab', $pjTerpakai)->get();

        return view('kendaraan.edit', compact('kendaraan', 'penanggungJawab'));
    }

    /* =======================
     * UPDATE (FIXED)
     * ======================= */
    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kategori = $request->kategori;

        $rules = [
            'kategori' => 'required|in:per_plat,operasional',
            'merek' => 'required|string|max:100',
            'jenis_kendaraan' => 'required|string|max:100',
            'warna' => 'required|string|max:50',
            'jenis_bahan_bakar' => 'required|string|max:50',
            // ✅ UNIQUE tapi kecuali dirinya sendiri
            'id_penanggung_jawab' => 'required|exists:penanggung_jawab,id_penanggung_jawab|unique:kendaraan,id_penanggung_jawab,' . $id . ',id_kendaraan',
        ];

        if ($kategori === 'per_plat') {
            $rules['no_plat'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        $kendaraan->update([
            'kategori' => $kategori,
            'no_plat' => $kategori === 'operasional' ? 'Operasional' : $validated['no_plat'],
            'merek' => $validated['merek'],
            'jenis_kendaraan' => $validated['jenis_kendaraan'],
            'warna' => $validated['warna'],
            'jenis_bahan_bakar' => $validated['jenis_bahan_bakar'],
            'id_penanggung_jawab' => $validated['id_penanggung_jawab'],
        ]);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diubah.');
    }

    /* =======================
     * DELETE
     * ======================= */
    public function destroy($id)
    {
        Kendaraan::findOrFail($id)->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil dihapus.');
    }
}