<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function inputKelas(Request $request)
    {
        try {
            $request->validate([
                'nama_kelas' => 'required|string|max:255',
            ]);
            DB::beginTransaction();
            $response = Kelas::create([
                'nama_kelas' => $request->nama_kelas,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Kelas berhasil ditambahkan',
                'data' => $response
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan kelas: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function fetchKelas()
    {
        try {
            $data = Kelas::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Data kelas berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data kelas: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function deleteKelas($id)
    {
        try {
            DB::beginTransaction();
            $kelas = Kelas::findOrFail($id);
            $kelas->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Kelas berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus kelas: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function updateKelas(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_kelas' => 'required|string|max:255',
            ]);
            DB::beginTransaction();
            $kelas = Kelas::findOrFail($id);
            $kelas->nama_kelas = $request->nama_kelas;
            $kelas->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Kelas berhasil diperbarui',
                'data' => $kelas
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui kelas: ' . $th->getMessage(),
            ], 500);
        }
    }
}
