<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DetalleBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blog = [
            [
                'titulo_blog' => 'Panel fibra de Bambú',
                'subtitulo_beneficio' => 'Futuro verde en la construcción  Beneficios del bambú',
                'id_blog' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'titulo_blog' => 'Panel fibra de Bambú',
                'subtitulo_beneficio' => 'Futuro verde en la construcción  Beneficios del bambú',
                'id_blog' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'titulo_blog' => 'Panel fibra de Bambú',
                'subtitulo_beneficio' => 'Futuro verde en la construcción  Beneficios del bambú',
                'id_blog' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'titulo_blog' => 'Panel fibra de Bambú',
                'subtitulo_beneficio' => 'Futuro verde en la construcción  Beneficios del bambú',
                'id_blog' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('detalle_blogs')->insert($blog);
    }
}
