<?php

namespace Database\Seeders;

use App\Models\Comentarios;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComentariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comentarios::create([
            'comentarios' => 'bienvenido'
        ]);
    }
}
