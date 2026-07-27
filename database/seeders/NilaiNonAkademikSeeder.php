<?php

namespace Database\Seeders;

use App\Models\NilaiNonAkademik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaiNonAkademikSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed data prestasi non-akademik (587 catatan) dari skripsi/data_non_akademik.xlsx.
     */
    public function run(): void
    {
        $rows = require __DIR__ . '/data_non_akademik.php';

        foreach ($rows as $row) {
            NilaiNonAkademik::create($row);
        }
    }
}
