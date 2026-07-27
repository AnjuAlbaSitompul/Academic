<?php

namespace App\Http\Controllers;

use App\Models\NilaiNonAkademik;
use Illuminate\Http\Request;

class NilaiNonAkademikController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'data' => NilaiNonAkademik::all()
            ]);
        }
        return view('nilai_non_akademik.index');
    }

    public function store(Request $request)
    {
        NilaiNonAkademik::create([
            'nama'                    => $request->nama,
            'jumlah_ekstrakurikuler'  => $request->jumlah_ekstrakurikuler,
            'jenis_kegiatan'          => $request->jenis_kegiatan,
            'tingkat_perlombaan'      => $request->tingkat_perlombaan,
            'perlombaan_diikuti'      => $request->perlombaan_diikuti,
            'kehadiran_ekstrakurikuler' => $request->kehadiran_ekstrakurikuler,
            'prestasi'                => $request->prestasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data prestasi non-akademik berhasil disimpan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $nilai = NilaiNonAkademik::findOrFail($id);
        $nilai->update([
            'nama'                    => $request->nama,
            'jumlah_ekstrakurikuler'  => $request->jumlah_ekstrakurikuler,
            'jenis_kegiatan'          => $request->jenis_kegiatan,
            'tingkat_perlombaan'      => $request->tingkat_perlombaan,
            'perlombaan_diikuti'      => $request->perlombaan_diikuti,
            'kehadiran_ekstrakurikuler' => $request->kehadiran_ekstrakurikuler,
            'prestasi'                => $request->prestasi,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        NilaiNonAkademik::destroy($id);

        return response()->json(['success' => true]);
    }
}
