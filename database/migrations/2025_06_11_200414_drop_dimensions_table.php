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
        Schema::dropIfExists('dimensions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')
                  ->constrained('productos')
                  ->onDelete('cascade');
            $table->enum('tipo', ['largo', 'alto', 'ancho']);
            $table->string('valor', 50);
            $table->timestamps();
        });
    }
};
