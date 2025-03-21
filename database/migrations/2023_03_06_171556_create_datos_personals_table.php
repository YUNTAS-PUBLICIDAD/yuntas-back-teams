<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosPersonalsTable extends Migration
{
    public function up()
    {
        Schema::create('datos_personals', function (Blueprint $table) {
            $table->id();
            $table->string('datos', 100);
            $table->enum('tipo_doc', ['DNI', 'Pasaporte', 'Carnet de Extranjeria']);
            $table->string('numero_doc', 11);
            $table->string('correo', 100);
            $table->string('telefono', 9);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('datos_personals');
    }
}