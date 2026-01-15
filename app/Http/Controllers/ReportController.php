<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        $nilai = Nilai::all();
        return view('report.index', compact('siswa', 'nilai'));
    }
}
