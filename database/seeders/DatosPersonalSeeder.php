<?php

namespace Database\Seeders;

use App\Models\DatosPersonal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatosPersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DatosPersonal::factory(10)->create();
    }
}
