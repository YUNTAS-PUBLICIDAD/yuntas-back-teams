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
        Schema::create('seccion', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sec')->autoIncrement();
            $table->unsignedBigInteger('id_com');
            $table->string('nombre', 100);
            $table->timestamps();

            $table->foreign('id_com')->references('id_com')->on('comentarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccion');
    }
};
