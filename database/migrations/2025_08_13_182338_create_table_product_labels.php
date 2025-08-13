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
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('meta_data');
        });

        Schema::create('producto_etiquetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("producto_id")->constrained("productos")->onDelete("cascade");
            $table->string('meta_titulo', 255)->nullable();
            $table->text('meta_descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->json('meta_data')->nullable();
        });

        Schema::dropIfExists('producto_etiquetas');
    }
};
