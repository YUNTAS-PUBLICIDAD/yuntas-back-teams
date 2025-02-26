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
        Schema::create('usuario_registro', function (Blueprint $table) {
            $table->unsignedBigInteger('id_userRegis')->autoIncrement();
            $table->unsignedBigInteger('id_sec');
            $table->string('nombre', 100);
            $table->string('correo', 100);
            $table->string('celular', 9);
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_registro');
    }
};
