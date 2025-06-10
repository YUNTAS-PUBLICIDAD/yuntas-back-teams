<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VideoBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $video = [
            [
                'url_video' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_video' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_video' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_video' => 'http://www.rosenbaum.info/est-enim-perspiciatis-voluptatem-dolore-beatae-eligendi',
                'titulo_video' => 'COMO UTILIZAR NUESTRO PRODUCTO',
                'id_blog' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('video_blogs')->insert($video);
    }
}
