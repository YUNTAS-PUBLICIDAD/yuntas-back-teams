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
        Schema::create('producto_relacionados', function (Blueprint $table) {
            $table->foreignId('id_producto')
                  ->constrained('productos')
                  ->onDelete('cascade');
            $table->foreignId('id_relacionado')
                  ->constrained('productos')
                  ->onDelete('cascade');
            $table->primary(['id_producto', 'id_relacionado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_relacionados');
    }
};
