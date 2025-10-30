<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blog_parrafos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');
            $table->text('parrafo');
            $table->integer('orden')->default(1);
            $table->timestamps();
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_parrafos');
    }
};
