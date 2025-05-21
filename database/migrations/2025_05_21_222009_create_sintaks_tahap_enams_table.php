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
        Schema::create('sintaks_tahap_enams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sintaks_id')->constrained('sintaks_barus')->onDelete('cascade');
            $table->string('link_presentasi')->nullable(); // Link Google Meet
            $table->dateTime('jadwal_presentasi')->nullable();
            $table->text('catatan_presentasi')->nullable();
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
        Schema::dropIfExists('sintaks_tahap_enams');
    }
};
