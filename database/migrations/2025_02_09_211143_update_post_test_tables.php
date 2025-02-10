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
        Schema::table('post_tests_attempt', function (Blueprint $table) {
            // Menambahkan kolom total_nilai jika belum ada
            $table->decimal('total_nilai', 5, 2)->default(0.00)->change();
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_tests_attempt', function (Blueprint $table) {
            $table->integer('total_nilai')->change();
        });
    }
};
