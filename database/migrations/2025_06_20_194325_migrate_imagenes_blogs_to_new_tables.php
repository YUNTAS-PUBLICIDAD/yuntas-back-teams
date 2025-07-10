<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear nueva tabla blogs_imagenes
        Schema::create('blogs_imagenes', function (Blueprint $table) {
            $table->id();
            $table->text('url_imagen');
            $table->unsignedBigInteger('id_blog');
            $table->foreign('id_blog')->references('id')->on('blogs')->onDelete('cascade');
            $table->timestamps();
        });

        // Crear nueva tabla blogs_parrafos
        Schema::create('blogs_parrafos', function (Blueprint $table) {
            $table->id();
            $table->text('parrafo_imagen');
            $table->unsignedBigInteger('id_blog');
            $table->foreign('id_blog')->references('id')->on('blogs')->onDelete('cascade');
            $table->timestamps();
        });

        // Migrar datos desde imagen_blogs a las nuevas tablas
        $registros = DB::table('imagen_blogs')->get();

        foreach ($registros as $registro) {
            DB::table('blogs_imagenes')->insert([
                'url_imagen' => $registro->url_imagen,
                'id_blog' => $registro->id_blog,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('blogs_parrafos')->insert([
                'parrafo_imagen' => $registro->parrafo_imagen,
                'id_blog' => $registro->id_blog,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Eliminar la tabla antigua
        Schema::dropIfExists('imagen_blogs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar imagen_blogs
        Schema::create('imagen_blogs', function (Blueprint $table) {
            $table->id();
            $table->text('url_imagen');
            $table->text('parrafo_imagen');
            $table->unsignedBigInteger('id_blog');
            $table->foreign('id_blog')->references('id')->on('blogs')->onDelete('cascade');
            $table->timestamps();
        });

        // (Opcional) Recomponer datos si lo deseas aquí

        Schema::dropIfExists('blogs_imagenes');
        Schema::dropIfExists('blogs_parrafos');
    }
};
