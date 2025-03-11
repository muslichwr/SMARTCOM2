<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ketuas', function (Blueprint $table) {
            // Hapus foreign key lama (jika ada)
            $table->dropForeign(['kelompok_id']);
            $table->dropForeign(['user_id']);
    
            // Tambahkan foreign key baru dengan onDelete('cascade')
            $table->foreign('kelompok_id')
                  ->references('id')
                  ->on('kelompoks')
                  ->onDelete('cascade');
    
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketuas');
    }
};
