<?php

namespace Database\Seeders;

use App\Models\Nilai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed data prestasi akademik (587 catatan) dari skripsi/kfold.sql.
     */
    public function run(): void
    {
        $rows = require __DIR__ . '/data_akademik.php';

        foreach ($rows as $row) {
            Nilai::create($row);
        }
    }
}
