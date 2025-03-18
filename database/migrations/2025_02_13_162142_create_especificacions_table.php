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
        Schema::create('especificacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')
                  ->constrained('productos')
                  ->onDelete('cascade');
            $table->string('clave', 100);
            $table->string('valor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especificacions');
    }
};
