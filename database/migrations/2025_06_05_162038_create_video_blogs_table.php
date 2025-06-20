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
        Schema::create('video_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('url_video');
            $table->string('titulo_video', 40);
            $table->unsignedBigInteger('id_blog');

            $table->foreign('id_blog')->references('id')->on('blogs')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_blogs');
    }
};
