<?php

namespace Database\Seeders;

use App\Models\Reclamo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReclamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reclamo::factory(10)->create();
    }
}
