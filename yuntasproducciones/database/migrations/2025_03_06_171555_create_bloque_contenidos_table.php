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
        Schema::create('bloque_contenidos', function (Blueprint $table) {
            $table->id('id_bloque');
            $table->unsignedBigInteger('id_blog')->nullable();
            $table->text('parrafo')->nullable();
            $table->string('imagen', 255)->nullable();
            $table->text('descripcion_imagen')->nullable();
            $table->integer('orden');
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fecha_actualizacion')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('id_blog')->references('id_blog')->on('blogs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloque_contenidos');
    }
};
