<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{

    public function index()
    {
        try {
            DB::beginTransaction();
            $kelas = Kelas::all();
            DB::commit();
            return view('siswa.index', compact('kelas'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
    public function fetchSiswa($kelasId = null)
    {
        try {
            DB::beginTransaction();
            if ($kelasId) {
                $siswas = Siswa::with('kelas')->where('kelas_id', $kelasId)->get();
            } else {
                $siswas = Siswa::with('kelas')->get();
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil diambil',
                'data' => $siswas
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data siswa: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function inputSiswa(Request $request)
    {
        try {
            $request->validate([
                'nama_siswa' => 'required|string|max:255',
                'kelas_id' => 'required|exists:kelas,id',
            ]);
            DB::beginTransaction();
            $siswa = Siswa::create([
                'nama_siswa' => $request->nama_siswa,
                'kelas_id' => $request->kelas_id,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $siswa
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan siswa: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function deleteSiswa($id)
    {
        try {
            DB::beginTransaction();
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Siswa berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus siswa: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function updateSiswa(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_siswa' => 'required|string|max:255',
                'kelas_id' => 'required|exists:kelas,id',
            ]);
            DB::beginTransaction();
            $siswa = Siswa::findOrFail($id);
            $siswa->update([
                'nama_siswa' => $request->nama_siswa,
                'kelas_id' => $request->kelas_id,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Siswa berhasil diperbarui',
                'data' => $siswa
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui siswa: ' . $th->getMessage(),
            ], 500);
        }
    }
}
