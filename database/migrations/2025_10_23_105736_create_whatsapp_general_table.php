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
        Schema::create('whatsapp_general', function (Blueprint $table) {
            $table->id();
            $table->text('caption')->nullable();
            $table->string('image', 2048)->nullable();
            $table->string('current_page', 100)->default("raiz");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_general');
    }
};
