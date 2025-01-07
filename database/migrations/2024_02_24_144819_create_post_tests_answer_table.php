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
        Schema::create('post_tests_answer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_test_attempt_id');
            $table->unsignedBigInteger('soal_id');
            $table->string('typed_answer');
            $table->integer('nilai')->nullable()->default(0);

            $table->foreign('post_test_attempt_id')->references('id')->on('post_tests_attempt')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tests_answer');
    }
};
