<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('blogs_imagenes', function (Blueprint $table) {
            $table->string('title')->nullable()->after('text_alt');
        });
    }

    public function down(): void
    {
        Schema::table('blogs_imagenes', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
