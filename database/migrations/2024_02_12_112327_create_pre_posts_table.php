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
        Schema::create('pre_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('materi_id');
            $table->string('judulPrePost');
            $table->string('slug');

            $table->foreign('materi_id')->references('id')->on('materis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_posts');
    }
};
