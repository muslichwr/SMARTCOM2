<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalNilaiToDecimalInPreTestAttemptsTable extends Migration
{
    public function up()
    {
        // Ubah tipe data kolom total_nilai menjadi decimal
        Schema::table('pre_tests_attempt', function (Blueprint $table) {
            $table->decimal('total_nilai', 5, 2)->change(); // 5 digit total, 2 digit di belakang koma
        });
    }

    public function down()
    {
        // Kembalikan tipe data kolom total_nilai ke integer (jika rollback)
        Schema::table('pre_tests_attempt', function (Blueprint $table) {
            $table->integer('total_nilai')->change();
        });
    }
}