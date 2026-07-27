<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\NilaiNonAkademik;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    /**
     * Halaman evaluasi model Decision Tree C4.5 + 10-Fold Cross Validation.
     * Menampilkan visualisasi pohon keputusan, information gain, distribusi
     * data latih/uji, hasil evaluasi per fold, confusion matrix, serta
     * tabel hasil prediksi untuk prestasi akademik & non-akademik.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'akademik' => Nilai::all(),
                'nonAkademik' => NilaiNonAkademik::all(),
            ]);
        }

        $totalAkademik = Nilai::count();
        $totalNonAkademik = NilaiNonAkademik::count();

        return view('evaluasi.index', compact('totalAkademik', 'totalNonAkademik'));
    }
}
