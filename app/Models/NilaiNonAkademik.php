<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiNonAkademik extends Model
{
    protected $table = 'non_akademiks';

    protected $fillable = [
        'nama',
        'jumlah_ekstrakurikuler',
        'jenis_kegiatan',
        'tingkat_perlombaan',
        'perlombaan_diikuti',
        'kehadiran_ekstrakurikuler',
        'prestasi',
    ];
}
