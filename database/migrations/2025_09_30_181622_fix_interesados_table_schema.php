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
        // 1. Agregamos las columnas que faltan
        $table->string('nombre')->after('producto_id');
        $table->string('email')->after('nombre');

        // 2. Hacemos que cliente_id sea opcional (nullable)
        $table->unsignedBigInteger('cliente_id')->nullable()->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
