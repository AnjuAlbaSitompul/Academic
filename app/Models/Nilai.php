<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilais';

    protected $fillable = [
        'nama',
        'nilai_uas',
        'nilai_uts',
        'nilai_un',
        'kehadiran',
        'keterlambatan',
        'prestasi'
    ];
}
