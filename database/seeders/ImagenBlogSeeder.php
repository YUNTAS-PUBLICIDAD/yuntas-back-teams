<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImagenBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $blog = [
            [
                'url_imagen' => 'https://i.imgur.com/oyiDoMN.png',
                'id_blog' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'id_blog' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'id_blog' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'id_blog' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'id_blog' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'id_blog' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'id_blog' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'url_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'id_blog' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('blogs_imagenes')->insert($blog);
    }
}
