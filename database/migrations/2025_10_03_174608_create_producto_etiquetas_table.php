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
        Schema::create('producto_etiquetas', function (Blueprint $table) {
             $table->id(); // Crea la columna 'id' (bigint, auto-increment, primary key)

            // Crea la columna para la relación con la tabla 'productos'
            $table->foreignId('producto_id')
                  ->constrained('productos') 
                  ->onDelete('cascade');  

            $table->string('meta_titulo', 250)->nullable(); 
            $table->text('meta_descripcion')->nullable();  
            $table->text('keywords')->nullable();          
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_etiquetas');
    }
};
