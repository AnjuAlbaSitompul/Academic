<?php

namespace App\Http\Controllers;

use App\Models\Method;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function main()
    {
        $accuracy = Method::latest('updated_at')->first()?->accuracy ?? 0;

        $totalNilai = Nilai::count();
        $lastNilaiUpdate = Nilai::latest('updated_at')->first()?->updated_at;

        $user = User::count();
        $lastUserUpdate = User::latest('updated_at')->first()?->updated_at;

        $siswa = Siswa::count();
        $lastSiswaUpdate = Siswa::latest('updated_at')->first()?->updated_at;

        $lastMethodUpdate = Method::latest('updated_at')->first()?->updated_at;

        return view('dashboard', compact(
            'accuracy',
            'totalNilai',
            'user',
            'siswa',
            'lastNilaiUpdate',
            'lastUserUpdate',
            'lastSiswaUpdate',
            'lastMethodUpdate'
        ));
    }
}
