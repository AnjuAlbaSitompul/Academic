<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'data' => Nilai::all()
            ]);
        }
        return view('nilai.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $siswaId = $request->siswa_id;
            Nilai::create([
                'nama'            => $request->nama,
                'nilai_uas'       => $request->nilai_uas,
                'nilai_uts'       => $request->nilai_uts,
                'nilai_un'        => $request->nilai_un,
                'kehadiran'       => $request->kehadiran,
                'keterlambatan'   => $request->keterlambatan,
                'prestasi'        => $request->prestasi,
            ]);
            Siswa::where('id', $siswaId)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data nilai berhasil disimpan dan siswa dipindahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->update([
            'nama'            => $request->nama,
            'nilai_uas'       => $request->nilai_uas,
            'nilai_uts'       => $request->nilai_uts,
            'nilai_un'        => $request->nilai_un,
            'kehadiran'       => $request->kehadiran,
            'keterlambatan'   => $request->keterlambatan,
            'prestasi'        => $request->prestasi,
        ]);
        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        Nilai::destroy($id);
        return response()->json(['success' => true]);
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File harus berupa CSV'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $file = fopen($request->file('file')->getRealPath(), 'r');
            $header = fgetcsv($file);
            if (!$header) {
                throw new \Exception('File CSV kosong');
            }
            $header = array_map(fn($h) => strtolower(trim($h)), $header);
            $expected = ['nama', 'nilai_uas', 'nilai_uts', 'nilai_un', 'kehadiran', 'keterlambatan', 'prestasi'];

            if ($header !== $expected) {
                throw new \Exception('Format CSV tidak sesuai template: ' . implode(',', $expected));
            }
            while (($row = fgetcsv($file)) !== false) {
                if (count(array_filter($row)) === 0) continue;
                Nilai::create([
                    'nama'          => trim($row[0]),
                    'nilai_uas'     => is_numeric($row[1]) ? $row[1] : 0,
                    'nilai_uts'     => is_numeric($row[2]) ? $row[2] : 0,
                    'nilai_un'      => is_numeric($row[3]) ? $row[3] : 0,
                    'kehadiran'     => is_numeric($row[4]) ? $row[4] : 0,
                    'keterlambatan' => is_numeric($row[5]) ? $row[5] : 0,
                    'prestasi'      => strtoupper(trim($row[6])),
                ]);
            }
            fclose($file);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Upload CSV berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
