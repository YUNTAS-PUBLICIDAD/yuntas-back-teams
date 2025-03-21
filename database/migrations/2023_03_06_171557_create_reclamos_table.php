<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReclamosTable extends Migration
{
    public function up()
    {
        Schema::create('reclamos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_compra');
            $table->string('producto', 50);
            $table->text('detalle_reclamo');
            $table->decimal('monto_reclamo', 10, 2);
            $table->unsignedBigInteger('id_data');

            $table->foreign('id_data')->references('id')->on('datos_personals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reclamos');
    }
}