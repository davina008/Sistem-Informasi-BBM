<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggaran;
use App\Models\Kendaraan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnggaranController extends Controller
{
    /* =======================
     * INDEX
     * ======================= */
    public function index(Request $request)
    {
        $tahun  = $request->tahun;
        $search = $request->search;

        $anggarans = Anggaran::with('kendaraan')
            ->when($tahun, fn ($q) => $q->where('tahun', $tahun))
            ->when($search, function ($q) use ($search) {
                $q->where('id_anggaran', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhereHas('kendaraan', function ($k) use ($search) {
                      $k->where('no_plat', 'like', "%{$search}%")
                        ->orWhere('merek', 'like', "%{$search}%");
                  });
            })
            #->orderBy('id_anggaran', 'asc')
            ->orderBy('tahun', 'desc')
            ->orderBy('sisa', 'asc')
            ->paginate(10);

        return view('anggaran.index', compact('anggarans', 'tahun', 'search'));
    }

    /* =======================
     * CREATE
     * ======================= */
    public function create()
    {
        return view('anggaran.create', [
            'kendaraan' => Kendaraan::all()
        ]);
    }

    /* =======================
     * GENERATE ID (AJAX)
     * ======================= */
    public function generateId()
    {
        $last = Anggaran::orderBy('id_anggaran', 'desc')->first();

        if (!$last) {
            $newId = 'AGR001';
        } else {
            $number = (int) substr($last->id_anggaran, 3);
            $newId  = 'AGR' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        }

        return response()->json([
            'id_anggaran' => $newId
        ]);
    }

    /* =======================
     * STORE
     * ======================= */
    public function store(Request $request)
    {
        $anggaranTotal = (int) str_replace('.', '', $request->anggaran_total);
        $pemakaian     = (int) str_replace('.', '', $request->pemakaian ?? 0);

        $request->merge([
            'anggaran_total' => $anggaranTotal,
            'pemakaian'      => $pemakaian
        ]);

        $request->validate([
            'id_anggaran'    => 'required|unique:anggaran,id_anggaran',
            'id_kendaraan'   => 'required',
            'tahun'          => 'required|digits:4',
            'anggaran_total' => 'required|numeric|min:0',
        ]);

        Anggaran::create([
            'id_anggaran'    => $request->id_anggaran,
            'id_kendaraan'   => $request->id_kendaraan,
            'tahun'          => $request->tahun,
            'anggaran_total' => $anggaranTotal,
            'pemakaian'      => $pemakaian,
            'sisa'           => max(0, $anggaranTotal - $pemakaian),
        ]);

        return redirect()
            ->route('anggaran.index')
            ->with('success', 'Data anggaran berhasil ditambahkan');
    }

    /* =======================
     * EDIT
     * ======================= */
    public function edit($id)
    {
        return view('anggaran.edit', [
            'anggaran'  => Anggaran::findOrFail($id),
            'kendaraan' => Kendaraan::all()
        ]);
    }

    /* =======================
     * UPDATE
     * ======================= */
    public function update(Request $request, $id)
    {
        $anggaranTotal = (int) str_replace('.', '', $request->anggaran_total);
        $pemakaian     = (int) str_replace('.', '', $request->pemakaian ?? 0);

        $request->merge([
            'anggaran_total' => $anggaranTotal,
            'pemakaian'      => $pemakaian
        ]);

        $request->validate([
            'id_kendaraan'   => 'required',
            'tahun'          => 'required|digits:4',
            'anggaran_total' => 'required|numeric|min:0',
        ]);

        Anggaran::findOrFail($id)->update([
            'id_kendaraan'   => $request->id_kendaraan,
            'tahun'          => $request->tahun,
            'anggaran_total' => $anggaranTotal,
            'pemakaian'      => $pemakaian,
            'sisa'           => max(0, $anggaranTotal - $pemakaian),
        ]);

        return redirect()
            ->route('anggaran.index')
            ->with('success', 'Data anggaran berhasil diperbarui');
    }

    /* =======================
     * DELETE
     * ======================= */
    public function destroy($id)
    {
        Anggaran::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }

    /* =======================
     * KIRIM NOTIFIKASI WA (FIXED)
     * ======================= */
    public function kirimNotif($id_anggaran)
    {
        $anggaran = Anggaran::with('kendaraan.penanggungJawab')
            ->where('id_anggaran', $id_anggaran)
            ->firstOrFail();

        $kendaraan = $anggaran->kendaraan;

        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan');
        }

        // 🔥 Ambil nomor dari accessor (FIX)
        $phone = $kendaraan->no_hp_pj;

        if (empty(trim($phone))) {
            return back()->with('error', 'Nomor penanggung jawab tidak tersedia');
        }

        $pesan = "Yth. " . $kendaraan->nama_pj . ",\n\n" .
         "Dengan hormat,\n\n" .
         "Berikut kami sampaikan informasi terkait anggaran kendaraan:\n\n" .
         "No. Plat           : " . $kendaraan->no_plat . "\n" .
         "Tahun             : " . $anggaran->tahun . "\n" .
         "Total Anggaran    : Rp " . number_format($anggaran->anggaran_total, 0, ',', '.') . "\n" .
         "Pemakaian         : Rp " . number_format($anggaran->pemakaian, 0, ',', '.') . "\n" .
         "Sisa Anggaran     : Rp " . number_format($anggaran->sisa, 0, ',', '.') . "\n\n" .
         "Status anggaran telah disetujui/diperbarui.\n\n" .
         "Demikian informasi ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.\n\n" .
         "Hormat kami,\n" .
         "Admin Sistem";

        $token   = config('services.fonnte.token');
        $baseUrl = 'https://api.fonnte.com';

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$baseUrl}/send", [
                'target'  => $phone,
                'message' => $pesan,
            ]);

            if ($response->successful() && $response->json('status') === true) {
                return back()->with('success', 'Notifikasi berhasil dikirim ke ' . $phone);
            }

            Log::warning('Fonnte send failed', [
                'phone'    => $phone,
                'response' => $response->json(),
            ]);

            return back()->with('error', 'Gagal mengirim notifikasi WhatsApp');

        } catch (\Exception $e) {
            Log::error('Fonnte exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }
}