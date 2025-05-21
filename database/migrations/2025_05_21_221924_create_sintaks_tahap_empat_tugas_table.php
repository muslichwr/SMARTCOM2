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
        Schema::create('sintaks_tahap_empat_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sintaks_pelaksanaan_id')->constrained('sintaks_tahap_empats')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Anggota yang bertanggung jawab
            $table->string('judul_task');
            $table->text('deskripsi_task')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['belum_mulai', 'proses', 'selesai'])->default('belum_mulai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sintaks_tahap_empat_tugas');
    }
};
