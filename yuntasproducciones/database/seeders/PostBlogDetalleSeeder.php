<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostBlogDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\BlogDetalle::factory(10)->create();
    }
}
