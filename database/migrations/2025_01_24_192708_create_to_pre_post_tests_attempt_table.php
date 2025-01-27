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
        Schema::create('pre_post_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pre_post_id');
            $table->unsignedBigInteger('soal_id');

            $table->foreign('pre_post_id')->references('id')->on('pre_posts')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_post_tests');
    }
};
