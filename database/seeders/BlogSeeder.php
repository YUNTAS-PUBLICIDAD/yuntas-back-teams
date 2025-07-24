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
                'producto_id' => 1,
                'subtitulo' => 'Panel de fibra de bamboo',
                'imagen_principal' => 'https://i.imgur.com/bKisDUE.png',
                'created_at' => Carbon::now(),
            ],
            [
                
                'producto_id' => 1,
                'subtitulo' => 'Soldadora lingba',
                'imagen_principal' => 'https://i.imgur.com/vgxpLns.png',
                'created_at' => Carbon::now(),
            ],
            [
                'producto_id' => 1,
                'subtitulo' => 'Soldadora spark',
                'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png',
                'created_at' => Carbon::now(),
            ],
            [
                'producto_id' => 1,
                'subtitulo' => 'Ventilador holográfico',
                'imagen_principal' => 'https://i.imgur.com/ZgElRO5.png',
                'created_at' => Carbon::now(),
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
