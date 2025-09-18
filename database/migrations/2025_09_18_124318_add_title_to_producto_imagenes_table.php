<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producto_imagenes', function (Blueprint $table) {
            $table->string('title')->nullable()->after('texto_alt_SEO');
        });
    }

    public function down(): void
    {
        Schema::table('producto_imagenes', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
