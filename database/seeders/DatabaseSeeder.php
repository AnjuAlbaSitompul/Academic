<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'password' => bcrypt('12345'),
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Guru',
            'password' => bcrypt('12345'),
            'email' => 'guru@gmail.com',
            'role' => 'guru',
        ]);

        $this->call([
            KelasSeeder::class,
            SiswaSeeder::class,
            GuruSeeder::class,
            NilaiSeeder::class,
            NilaiNonAkademikSeeder::class,
        ]);
    }
}
