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
        Schema::create('post_tests_attempt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pre_post_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('status')->default(0);
            $table->string('link_github')->nullable();

            $table->foreign('pre_post_id')->references('id')->on('pre_posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tests_attempt');
    }
};
