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
        Schema::create('sintaks_tahap_delapan_refleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sintaks_evaluasi_id')->constrained('sintaks_tahap_delapans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Siswa yang memberi refleksi
            $table->text('refleksi_pribadi')->nullable();
            $table->text('kendala_dihadapi')->nullable();
            $table->text('pembelajaran_didapat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sintaks_tahap_delapan_refleksis');
    }
};
