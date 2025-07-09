<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blog = [
            [
                'link' => 'panel-fibra-bamboo',
                'producto_id' => 1,
                'titulo' => 'Panel de fibra de bamboo',
                'subtitulo1' => 'Panel de fibra de bamboo',
                'subtitulo2' => 'Panel de fibra de bamboo',
                'subtitulo3' => 'Panel de fibra de bamboo',
                'video_url' => 'https://www.youtube.com/watch?v=example1',
                'video_titulo' => 'Panel de fibra de bamboo',
                'imagen_principal' => 'https://i.imgur.com/bKisDUE.png',
                'created_at' => Carbon::now(),
            ],
            [
                'link' => 'soldadora-lingba',
                'producto_id' => 1,
                'titulo' => 'Soldadora lingba',
                'subtitulo1' => 'Soldadora lingba',
                'subtitulo2' => 'Soldadora lingba',
                'subtitulo3' => 'Soldadora lingba',
                'video_url' => 'https://www.youtube.com/watch?v=example2',
                'video_titulo' => 'Soldadora lingba',
                'imagen_principal' => 'https://i.imgur.com/vgxpLns.png',
                'created_at' => Carbon::now(),
            ],
            [
                'link' => 'soldadora-spark',
                'producto_id' => 1,
                'titulo' => 'Soldadora spark',
                'subtitulo1' => 'Soldadora spark',
                'subtitulo2' => 'Soldadora spark',
                'subtitulo3' => 'Soldadora spark',
                'video_url' => 'https://www.youtube.com/watch?v=example3',
                'video_titulo' => 'Soldadora spark',
                'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png',
                'created_at' => Carbon::now(),
            ],
            [
                'link' => 'ventilador-holografico',
                'producto_id' => 1,
                'titulo' => 'Ventilador holográfico',
                'subtitulo1' => 'Ventilador holográfico',
                'subtitulo2' => 'Ventilador holográfico',
                'subtitulo3' => 'Ventilador holográfico',
                'video_url' => 'https://www.youtube.com/watch?v=example4',
                'video_titulo' => 'Ventilador holográfico',
                'imagen_principal' => 'https://i.imgur.com/ZgElRO5.png',
                'created_at' => Carbon::now(),
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
