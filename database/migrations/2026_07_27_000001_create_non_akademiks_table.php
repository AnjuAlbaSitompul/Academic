<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('non_akademiks', function (Blueprint $table) {
            $table->id(); // auto increment
            $table->string('nama');
            $table->integer('jumlah_ekstrakurikuler');
            $table->enum('jenis_kegiatan', [
                'Futsal',
                'Bola Voli',
                'Catur',
                'Fashion Show',
                'English Club',
                'Seni Tari',
                'KIR',
                'PMR',
                'Pramuka',
                'Paduan Suara',
                'Rohis',
                'Paskibra',
            ]);
            $table->enum('tingkat_perlombaan', ['Sekolah', 'Kabupaten', 'Provinsi', 'Nasional', '-']);
            $table->integer('perlombaan_diikuti');
            $table->integer('kehadiran_ekstrakurikuler');
            $table->enum('prestasi', ['YA', 'TIDAK']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_akademiks');
    }
};
