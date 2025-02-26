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
        Schema::create('detalle_blog', function (Blueprint $table) {
            $table->unsignedBigInteger('id_blog_detalle')->autoIncrement();
            $table->unsignedBigInteger('id_blog');
            $table->string('descripcion', 40);
            $table->string('parrafo_01', 340);
            $table->string('parrafo_02', 500);
            $table->string('parrafo_03', 275);
            $table->string('img_01', 100);
            $table->string('img_02', 100);
            $table->string('img_03', 100);
            $table->timestamps();

            $table->foreign('id_blog')->references('id_blog')->on('blog')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_blog');
    }
};
