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
        Schema::create('sintaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materis');
            $table->foreignId('kelompok_id')->constrained('kelompoks');
            $table->enum('status_tahap', ['tahap_1', 'tahap_2', 'tahap_3', 'tahap_4', 'tahap_5', 'tahap_6', 'tahap_7']);
            $table->text('orientasi_masalah')->nullable();
            $table->text('rumusan_masalah')->nullable();
            $table->text('indikator_masalah')->nullable();
            $table->text('hasil_analisis')->nullable();
            $table->text('deskripsi_proyek')->nullable();
            $table->json('tugas_anggota')->nullable(); // Tugas anggota dalam format JSON
            $table->string('file_jadwal')->nullable(); // File jadwal yang diupload
            $table->json('to_do_list')->nullable(); // Daftar to-do list anggota dalam format JSON
            $table->string('file_proyek')->nullable(); // File proyek yang diupload
            $table->string('file_laporan')->nullable(); // File laporan yang diupload
            $table->enum('status_validasi', ['valid', 'invalid', 'pending'])->default('invalid');
            $table->longText('feedback_guru')->nullable();
            // Kolom untuk nilai dan evaluasi
            $table->integer('score_class_object')->nullable(); // Nilai untuk Class dan Object
            $table->integer('score_encapsulation')->nullable(); // Nilai untuk Encapsulation
            $table->integer('score_inheritance')->nullable(); // Nilai untuk Inheritance
            $table->integer('score_logic_function')->nullable(); // Nilai untuk Function and Logic
            $table->integer('score_project_report')->nullable(); // Nilai untuk Project Report
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sintak');
    }
};
