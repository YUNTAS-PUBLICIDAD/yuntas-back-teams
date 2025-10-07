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
        Schema::table('interesados', function (Blueprint $table) {
            $table->dropColumn([
                'titulo',
                'parrafo1',
                'imagen_prin',
                'imagenes_sec'
                   ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interesados', function (Blueprint $table) {
            
            $table->string('titulo', 250)->nullable();
            $table->string('parrafo1', 250)->nullable();
            $table->string('imagen_prin', 250)->nullable();
            $table->longText('imagenes_sec')->nullable();

        });
    }
};
