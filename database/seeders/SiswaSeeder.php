<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('seeders/data_akademik.php');

        foreach ($data as $siswa) {
            DB::table('siswa')->insert([
                'nama_siswa' => $siswa['nama'],
                'kelas_id' => 1, // sesuaikan dengan id kelas
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
