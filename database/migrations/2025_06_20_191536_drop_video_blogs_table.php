<?php

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
        Schema::table('blogs',function(Blueprint $table) {
            $table->string('video_url')->nullable()->after('subtitulo3');
            $table->string('video_titulo', 40)->nullable()->after('video_url');
        });

        $blogs = DB::table('blogs')->select('id')->get();

        foreach($blogs as $blog) {
            $video = DB::table('video_blogs')
                ->where('id_blog', $blog->id)
                ->first();

            if ($video) {
                DB::table('blogs')
                    ->where('id', $blog->id)
                    ->update([
                        'video_url' => $video->url_video,
                        'video_titulo' => $video->titulo_video
                    ]);
            }
        }

        Schema::dropIfExists('video_blogs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('video_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('url_video');
            $table->string('titulo_video', 40);
            $table->unsignedBigInteger('id_blog');

            $table->foreign('id_blog')->references('id')->on('blogs')->onDelete('cascade');

            $table->timestamps();
        });
    }
};
