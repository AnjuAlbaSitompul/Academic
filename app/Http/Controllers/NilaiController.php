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
        $request->validate([
            'nama' => 'required|string|max:255',
            'nilai_uas' => 'required|integer|min:0|max:100',
            'nilai_uts' => 'required|integer|min:0|max:100',
            'nilai_un' => 'required|integer|min:0|max:100',
            'kehadiran' => 'required|integer|min:0|max:100',
            'keterlambatan' => 'required|integer|min:0',
            'prestasi' => 'required|in:YA,TIDAK',
            'siswa_id' => 'nullable|exists:siswa,id',
        ]);
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'nilai_uas' => 'required|integer|min:0|max:100',
            'nilai_uts' => 'required|integer|min:0|max:100',
            'nilai_un' => 'required|integer|min:0|max:100',
            'kehadiran' => 'required|integer|min:0|max:100',
            'keterlambatan' => 'required|integer|min:0',
            'prestasi' => 'required|in:YA,TIDAK',
        ]);
        $nilai = Nilai::find($id);
        if (!$nilai) {
            return response()->json(['success' => false, 'message' => 'Data nilai tidak ditemukan'], 404);
        }
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
        $deleted = Nilai::destroy($id);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data nilai tidak ditemukan'], 404);
        }
        return response()->json(['success' => true]);
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File harus berupa CSV (maks 2MB)'
            ], 422);
        }

        $path = $request->file('file')->getRealPath();
        if (!$path || !is_readable($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak dapat dibaca'
            ], 422);
        }

        DB::beginTransaction();

        $file = null;
        try {
            $file = fopen($path, 'r');
            if (!$file) {
                throw new \Exception('File tidak dapat dibuka');
            }
            $header = fgetcsv($file);
            if (!$header) {
                throw new \Exception('File CSV kosong');
            }
            $header = array_map(fn($h) => strtolower(trim((string) $h, " \t\n\r\0\x0B\xEF\xBB\xBF")), $header);
            $expected = ['nama', 'nilai_uas', 'nilai_uts', 'nilai_un', 'kehadiran', 'keterlambatan', 'prestasi'];

            if ($header !== $expected) {
                throw new \Exception('Format CSV tidak sesuai template: ' . implode(',', $expected));
            }
            $rowNumber = 1;
            while (($row = fgetcsv($file)) !== false) {
                $rowNumber++;
                if (count($row) < 7 || count(array_filter($row, fn($v) => trim((string) $v) !== '')) === 0) continue;
                $prestasi = strtoupper(trim((string) $row[6]));
                if (!in_array($prestasi, ['YA', 'TIDAK'], true)) {
                    throw new \Exception("Kolom prestasi baris {$rowNumber} harus YA atau TIDAK");
                }
                Nilai::create([
                    'nama'          => trim($row[0]),
                    'nilai_uas'     => is_numeric($row[1]) ? $row[1] : 0,
                    'nilai_uts'     => is_numeric($row[2]) ? $row[2] : 0,
                    'nilai_un'      => is_numeric($row[3]) ? $row[3] : 0,
                    'kehadiran'     => is_numeric($row[4]) ? $row[4] : 0,
                    'keterlambatan' => is_numeric($row[5]) ? $row[5] : 0,
                    'prestasi'      => $prestasi,
                ]);
            }
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
        } finally {
            if (is_resource($file)) {
                fclose($file);
            }
        }
    }
}
