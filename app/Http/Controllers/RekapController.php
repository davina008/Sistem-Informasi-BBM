<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekap;
use App\Models\Anggaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RekapController extends Controller
{
    /**
     * 📋 Menampilkan daftar rekap dengan filter (bulan, tahun, plat, pencarian)
     */
    public function index(Request $request)
    {
        // Tambahkan list tahun untuk dropdown 2020-2035
        $listTahun = range(2020, 2035);

        $rekaps = Rekap::with(['anggaran.kendaraan'])
            ->when($request->bulan, fn($q) => $q->whereMonth('tgl', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tgl', $request->tahun))
            ->when($request->plat, fn($q) => $q->whereHas('anggaran.kendaraan', function ($sub) use ($request) {
                $sub->where('no_plat', 'like', "%{$request->plat}%");
            }))
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('id_rekap', 'like', "%{$search}%")
                        ->orWhereHas('anggaran', function ($sub) use ($search) {
                            $sub->where('id_anggaran', 'like', "%{$search}%")
                                ->orWhereHas('kendaraan', fn($sub2) =>
                                    $sub2->where('no_plat', 'like', "%{$search}%")
                                );
                        });
                });
            })
            ->orderByDesc('tgl')
            ->orderByDesc(DB::raw("CAST(SUBSTRING(id_rekap, 4) AS UNSIGNED)"))
            ->paginate(10)
            ->appends($request->query());

        // Kirim $listTahun ke view
        return view('rekap.index', compact('rekaps', 'listTahun'));
    }

    /**
     * 🆕 Form tambah data rekap
     */
    public function create()
    {
        $anggaran = Anggaran::with('kendaraan')->get();

        $last = Rekap::orderByDesc(DB::raw("CAST(SUBSTRING(id_rekap, 4) AS UNSIGNED)"))->first();
        $newId = $last
            ? 'RKP' . str_pad(((int) substr($last->id_rekap, 3)) + 1, 3, '0', STR_PAD_LEFT)
            : 'RKP001';

        return view('rekap.create', compact('anggaran', 'newId'));
    }

    /**
     * 💾 Simpan data rekap baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_rekap'    => 'required|string|max:10|unique:rekap,id_rekap',
            'tgl'         => 'required|date',
            'pengeluaran' => 'required|numeric|min:1',
            'id_anggaran' => 'required|exists:anggaran,id_anggaran',
            'bukti_struk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $buktiPath = $request->file('bukti_struk')->store('rekap', 'public');

            $rekap = Rekap::create([
                'id_rekap'     => $request->id_rekap,
                'tgl'          => $request->tgl,
                'pengeluaran'  => $request->pengeluaran,
                'id_anggaran'  => $request->id_anggaran,
                'bukti_struk'  => $buktiPath,
            ]);

            $rekap->anggaran?->updateTotalPemakaian();

            DB::commit();
            return redirect()->route('rekap.index')->with('success', 'Data rekap berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (!empty($buktiPath) && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * ✏️ Form edit data rekap
     */
    public function edit(string $id)
    {
        $rekap = Rekap::with('anggaran.kendaraan')->findOrFail($id);
        $anggaran = Anggaran::with('kendaraan')->get();

        return view('rekap.edit', compact('rekap', 'anggaran'));
    }

    /**
     * 🔄 Update data rekap
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_anggaran'  => 'required|exists:anggaran,id_anggaran',
            'tgl'          => 'required|date',
            'pengeluaran'  => 'required|numeric|min:1',
            'bukti_struk'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $rekap = Rekap::findOrFail($id);
            $buktiPath = $rekap->bukti_struk;

            if ($request->hasFile('bukti_struk')) {
                if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                    Storage::disk('public')->delete($buktiPath);
                }
                $buktiPath = $request->file('bukti_struk')->store('rekap', 'public');
            }

            $rekap->update([
                'id_anggaran' => $request->id_anggaran,
                'tgl'         => $request->tgl,
                'pengeluaran' => $request->pengeluaran,
                'bukti_struk' => $buktiPath,
            ]);

            $rekap->anggaran?->updateTotalPemakaian();

            DB::commit();
            return redirect()->route('rekap.index')->with('success', 'Data rekap berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * 🗑️ Hapus data rekap
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $rekap = Rekap::findOrFail($id);
            $idAnggaran = $rekap->id_anggaran;

            if ($rekap->bukti_struk && Storage::disk('public')->exists($rekap->bukti_struk)) {
                Storage::disk('public')->delete($rekap->bukti_struk);
            }

            $rekap->delete();
            Anggaran::find($idAnggaran)?->updateTotalPemakaian();

            DB::commit();
            return redirect()->route('rekap.index')->with('success', 'Data rekap berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * 📤 Export Rekap ke PDF
     */
    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $plat  = $request->get('plat');

        $rekaps = Rekap::with(['anggaran.kendaraan'])
            ->when($bulan, fn($q) => $q->whereMonth('tgl', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tgl', $tahun))
            ->when($plat, fn($q) => $q->whereHas('anggaran.kendaraan', fn($sub) =>
                $sub->where('no_plat', 'like', "%{$plat}%")
            ))
            ->orderBy('tgl', 'asc')
            ->take(300)
            ->get();

        if ($rekaps->isEmpty()) {
            return back()->with('warning', 'Tidak ada data rekap yang cocok untuk diexport.');
        }

        $totalPengeluaran = $rekaps->sum('pengeluaran');

        if ($bulan) {
            try {
                $namaBulan = Carbon::createFromDate(null, (int)$bulan, 1)->translatedFormat('F');
            } catch (\Throwable $e) {
                $namaBulan = 'Bulan Tidak Dikenal';
            }
            $periode = "{$namaBulan} " . ($tahun ?? date('Y'));
        } else {
            $periode = 'Semua Bulan ' . ($tahun ?? date('Y'));
        }

        $logo1 = public_path('logo/logo_sleman.png');
        $logo2 = public_path('logo/logo_dinas.png');

        $filename = 'Rekap_BBM_' .
            ($plat ? str_replace(' ', '_', strtoupper($plat)) : 'SemuaPlat') . '_' .
            ($bulan ?: 'SemuaBulan') . '_' .
            ($tahun ?: date('Y')) . '.pdf';

        $pdf = Pdf::loadView('rekap.export_pdf', [
            'rekaps'         => $rekaps,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'plat'           => $plat,
            'periode'        => $periode,
            'totalPengeluaran' => $totalPengeluaran,
            'logoKiri'       => file_exists($logo1) ? base64_encode(file_get_contents($logo1)) : null,
            'logoKanan'      => file_exists($logo2) ? base64_encode(file_get_contents($logo2)) : null,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }
}
