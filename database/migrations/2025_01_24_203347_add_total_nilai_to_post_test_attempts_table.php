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
            $table->integer('total_nilai')->default(0)->after('status'); // Tambahkan kolom total_nilai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_tests_attempt', function (Blueprint $table) {
            //
        });
    }
};
