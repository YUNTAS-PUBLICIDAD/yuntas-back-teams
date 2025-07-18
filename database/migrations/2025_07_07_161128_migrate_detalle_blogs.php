<?php

use GuzzleHttp\Psr7\DroppingStream;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $blogs = DB::table('blogs')->select('id')->get();

        foreach($blogs as $blogs){
            $subtititulo = DB::table('detalle_blogs')
                ->where('id_blog', $blogs->id)
                ->value('subtitulo_beneficio');
            DB::table('blogs')
                ->where('id', $blogs->id)
                ->update(['subtitulo3' => $subtititulo]);
        }

        Schema::dropIfExists('detalle_blogs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('detalle_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('titulo_blog', 80);
            $table->string('subtitulo_beneficio', 80);
            $table->unsignedBigInteger('id_blog');

            $table->foreign('id_blog')->references('id')->on('blogs')->onDelete('cascade');
            
            $table->timestamps();
        });
    }
};
