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
            $table->renameColumn('parrafo', 'subtitulo1');
            $table->renameColumn('descripcion', 'subtitulo2');
            $table->text('subtitulo3')->after('subtitulo2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->renameColumn('subtitulo1', 'parrafo');
            $table->renameColumn('subtitulo2', 'descripcion');
            $table->dropColumn('subtitulo3');
        });
    }
};
