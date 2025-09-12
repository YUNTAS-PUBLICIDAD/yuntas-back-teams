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
        Schema::table('producto_imagenes', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('texto_alt_SEO');
        });
    }

    public function down()
    {
        Schema::table('producto_imagenes', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
