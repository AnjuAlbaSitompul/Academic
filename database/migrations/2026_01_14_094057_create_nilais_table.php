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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id(); // auto increment
            $table->string('nama');
            $table->integer('nilai_uas');
            $table->integer('nilai_uts');
            $table->integer('nilai_un');
            $table->integer('kehadiran');
            $table->integer('keterlambatan');
            $table->enum('prestasi', ['YA', 'TIDAK']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
