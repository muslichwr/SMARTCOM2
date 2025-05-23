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
        Schema::create('sintaks_tahap_tigas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sintaks_id')->constrained('sintaks_barus')->onDelete('cascade');
            $table->string('file_jadwal')->nullable(); // File PDF/Word yang diupload siswa, admin/guru bisa mengedit.
            $table->date('tanggal_mulai')->nullable(); // Diisi oleh siswa, admin/guru bisa mengedit.
            $table->date('tanggal_selesai')->nullable(); // Diisi oleh siswa, admin/guru bisa mengedit.
            $table->enum('status', ['belum_mulai', 'proses', 'selesai'])->default('belum_mulai');
            $table->enum('status_validasi', ['valid', 'invalid', 'pending'])->default('pending');
            $table->longText('feedback_guru')->nullable(); // Opsional: untuk komentar saat invalid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sintaks_tahap_tigas');
    }
};
