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
                'ruta_imagen' => 'https://i.imgur.com/oyiDoMN.png',
                'blog_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ruta_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'blog_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ruta_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'blog_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ruta_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'blog_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ruta_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'blog_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ruta_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'blog_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ruta_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'blog_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ruta_imagen' => 'https://i.imgur.com/yOc0ofa.png',
                'blog_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('blogs_imagenes')->insert($blog);
    }
}
