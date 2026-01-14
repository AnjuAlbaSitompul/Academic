<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index()
    {
        try {
            DB::beginTransaction();
            $kelas = Kelas::all();
            DB::commit();
            return view('guru.index', compact('kelas'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function fetchGuru($kelasId = null)
    {
        try {
            DB::beginTransaction();
            if ($kelasId) {
                $gurus = Guru::with('kelas')->where('kelas_id', $kelasId)->get();
            } else {
                $gurus = Guru::with('kelas')->get();
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data guru berhasil diambil',
                'data' => $gurus
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data guru: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function inputGuru(Request $request)
    {
        try {
            $request->validate([
                'nama_guru' => 'required|string|max:255',
                'kelas_id' => 'required|exists:kelas,id',
            ]);
            DB::beginTransaction();
            $guru = Guru::create([
                'nama_guru' => $request->nama_guru,
                'kelas_id' => $request->kelas_id,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Guru berhasil ditambahkan',
                'data' => $guru
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan guru: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function deleteGuru($id)
    {
        try {
            DB::beginTransaction();
            $guru = Guru::findOrFail($id);
            $guru->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Guru berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus guru: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function updateGuru(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_guru' => 'required|string|max:255',
                'kelas_id' => 'required|exists:kelas,id',
            ]);
            DB::beginTransaction();
            $guru = Guru::findOrFail($id);
            $guru->update([
                'nama_guru' => $request->nama_guru,
                'kelas_id' => $request->kelas_id,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Guru berhasil diperbarui',
                'data' => $guru
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui guru: ' . $th->getMessage(),
            ], 500);
        }
    }
}
