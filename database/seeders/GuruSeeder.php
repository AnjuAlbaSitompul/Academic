<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('guru')->insert([
            [
                'nama_guru' => 'Bapak Andi Saputra',
                'kelas_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_guru' => 'Ibu Siti Rahma',
                'kelas_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_guru' => 'Bapak Ahmad Fauzi',
                'kelas_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_guru' => 'Ibu Dewi Lestari',
                'kelas_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_guru' => 'Bapak Rudi Hartono',
                'kelas_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_guru' => 'Ibu Maya Putri',
                'kelas_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
