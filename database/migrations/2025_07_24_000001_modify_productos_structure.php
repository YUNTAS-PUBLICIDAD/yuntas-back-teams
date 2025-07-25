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
        // 1. Eliminar tabla productos_relacionados si existe
        Schema::dropIfExists('productos_relacionados');
        
        // 2. Modificar tabla productos
        Schema::table('productos', function (Blueprint $table) {
            // Eliminar columnas que ya no se usarán
            $table->dropColumn(['subtitulo', 'lema', 'stock', 'precio']);
            
            // Agregar nuevas columnas para especificaciones y beneficios como JSON
            $table->json('especificaciones')->nullable()->comment('Especificaciones del producto en formato JSON');
            $table->json('beneficios')->nullable()->comment('Beneficios del producto en formato JSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar tabla productos_relacionados
        Schema::create('productos_relacionados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('relacionado_id')->constrained('productos')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['producto_id', 'relacionado_id']);
        });
        
        // Restaurar columnas eliminadas en productos
        Schema::table('productos', function (Blueprint $table) {
            // Eliminar las nuevas columnas
            $table->dropColumn(['especificaciones', 'beneficios']);
            
            // Restaurar las columnas eliminadas
            $table->string('subtitulo')->nullable();
            $table->string('lema')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('precio', 10, 2)->default(0.00);
        });
    }
};
