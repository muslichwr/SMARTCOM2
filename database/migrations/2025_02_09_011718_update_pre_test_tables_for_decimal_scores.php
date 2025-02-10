<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePreTestTablesForDecimalScores extends Migration
{
    public function up()
    {
        // Ubah tipe data kolom total_nilai di tabel pre_tests_attempt
        Schema::table('pre_tests_attempt', function (Blueprint $table) {
            $table->decimal('total_nilai', 5, 2)->default(0.00)->change();
        });

        Schema::table('pre_tests_answer', function (Blueprint $table) {
            $table->decimal('nilai', 5, 2)->default(0.00)->change();
        });
    }

    public function down()
    {
        // Kembalikan tipe data kolom total_nilai ke integer
        Schema::table('pre_tests_attempt', function (Blueprint $table) {
            $table->decimal('total_nilai', 5, 2)->change(); // 5 digit total, 2 digit di belakang koma
        });

        // Kembalikan tipe data kolom nilai ke integer
        Schema::table('pre_tests_answer', function (Blueprint $table) {
            $table->integer('nilai')->default(0)->change();
        });
    }
}