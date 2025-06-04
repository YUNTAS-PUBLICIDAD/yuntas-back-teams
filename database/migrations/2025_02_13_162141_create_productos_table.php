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
        Schema::create('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('nombre');
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->string('lema')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('imagen_principal');
            $table->integer('stock')->default(0);
            $table->decimal('precio', 10, 2);
            $table->string('seccion', 100)->nullable();
            $table->text('mensaje_correo')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
