<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            AssignPermissionsToRolesSeeder::class,
            AssignRoleToUserSeeder::class,

            // Productos
            ProductoSeeder::class,
            DimensionSeeder::class,
            ImagenProductoSeeder::class,
            ProductoRelacionadoSeeder::class,

            // Blogs
            BlogSeeder::class,
            DetalleBlogSeeder::class,
            ImagenBlogSeeder::class,
            VideoBlogSeeder::class,

            //Reclamos
            DatosPersonalSeeder::class,
            ReclamoSeeder::class,
        ]);
    }
}
