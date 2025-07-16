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
        $blogs = DB::table('blogs')->select('id')->get();

        foreach($blogs as $blogs){
            $subtititulo = DB::table('detalle_blogs')
                ->where('id_blog', $blogs->id)
                ->value('subtitulo_beneficio');
            DB::table('blogs')
                ->where('id', $blogs->id)
                ->update(['subtitulo3' => $subtititulo]);
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
