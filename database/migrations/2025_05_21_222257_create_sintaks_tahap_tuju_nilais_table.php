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
        Schema::create('sintaks_tahap_tuju_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sintaks_penilaian_id')->constrained('sintaks_tahap_tujus')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Siswa yang dinilai
            $table->json('nilai_kriteria')->nullable(); // Menyimpan semua kriteria penilaian dalam format JSON
            $table->integer('total_nilai_individu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sintaks_tahap_tuju_nilais');
    }
};
