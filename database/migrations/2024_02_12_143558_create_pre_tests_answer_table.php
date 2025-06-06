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
        Schema::create('pre_tests_answer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pre_test_attempt_id');
            $table->unsignedBigInteger('soal_id');
            $table->string('typed_answer');
            // $table->string('link_github');

            $table->foreign('pre_test_attempt_id')->references('id')->on('pre_tests_attempt')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_tests_answer');
    }
};
