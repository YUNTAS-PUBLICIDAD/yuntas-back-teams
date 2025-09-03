<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->unique(['email', 'producto_id']);
            $table->unique(['celular', 'producto_id']);
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropUnique(['email', 'producto_id']);
            $table->dropUnique(['celular', 'producto_id']);
        });
    }
};
