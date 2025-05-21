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
        Schema::create('sintaks_barus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks');
            $table->foreignId('materi_id')->constrained('materis');
            $table->enum('status_validasi', ['valid', 'invalid', 'pending'])->default('pending');
            $table->longText('feedback_guru')->nullable();
            $table->integer('total_nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sintaks_barus');
    }
};
