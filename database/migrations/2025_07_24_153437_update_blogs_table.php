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
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('link');
            $table->dropColumn('titulo');
            $table->dropColumn('subtitulo2');
            $table->dropColumn('video_url');
            $table->dropColumn('video_titulo');
            $table->renameColumn('subtitulo1', 'subtitulo');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
        $table->string('link')->nullable();
        $table->string('titulo')->nullable();
        $table->string('subtitulo2')->nullable();
        $table->string('video_url')->nullable();
        $table->string('video_titulo')->nullable();
        $table->renameColumn('subtitulo', 'subtitulo1');
    });
    }
};
