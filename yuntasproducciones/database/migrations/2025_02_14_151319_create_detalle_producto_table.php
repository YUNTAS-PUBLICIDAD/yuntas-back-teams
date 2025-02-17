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
        Schema::create('detalle_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('id_detalle_prod')->autoIncrement();
            $table->unsignedBigInteger('id_produc');
            $table->string('especificacion', 40 );
            $table->string('informacion', 255);
            $table->string('beneficios_01, 40');
            $table->string('beneficios_02, 40');
            $table->string('beneficios_03, 40');
            $table->string('beneficios_04, 40');
            $table->string('img_card, 100');
            $table->string('img_portada_01, 100');
            $table->string('img_portada_02, 100');
            $table->string('img_portada_03, 100');
            $table->string('img_esp, 100');
            $table->string('img_benef, 100');
            $table->timestamps();

            $table->foreign('id_produc')->references('id_produc')->on('producto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_producto');
    }
};
