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
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah_ekstrakurikuler' => 'required|integer|min:1|max:5',
            'jenis_kegiatan' => 'required|in:Futsal,Bola Voli,Catur,Fashion Show,English Club,Seni Tari,KIR,PMR,Pramuka,Paduan Suara,Rohis,Paskibra',
            'tingkat_perlombaan' => 'required|in:Sekolah,Kabupaten,Provinsi,Nasional,-',
            'perlombaan_diikuti' => 'required|integer|min:0|max:10',
            'kehadiran_ekstrakurikuler' => 'required|integer|min:60|max:100',
            'prestasi' => 'required|in:YA,TIDAK',
        ]);
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah_ekstrakurikuler' => 'required|integer|min:1|max:5',
            'jenis_kegiatan' => 'required|in:Futsal,Bola Voli,Catur,Fashion Show,English Club,Seni Tari,KIR,PMR,Pramuka,Paduan Suara,Rohis,Paskibra',
            'tingkat_perlombaan' => 'required|in:Sekolah,Kabupaten,Provinsi,Nasional,-',
            'perlombaan_diikuti' => 'required|integer|min:0|max:10',
            'kehadiran_ekstrakurikuler' => 'required|integer|min:60|max:100',
            'prestasi' => 'required|in:YA,TIDAK',
        ]);
        $nilai = NilaiNonAkademik::find($id);
        if (!$nilai) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
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
        $deleted = NilaiNonAkademik::destroy($id);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['success' => true]);
    }
}
