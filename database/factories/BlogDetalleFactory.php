<?php

namespace Database\Factories;

use App\Models\BlogDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogDetalle>
 */
class BlogDetalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_blog' => \App\Models\Blog::factory(),
            'descripcion' => $this->faker->text(40),
            'parrafo_01' => $this->faker->text(340),
            'parrafo_02' => $this->faker->text(500),
            'parrafo_03' => $this->faker->text(275),
            'img_01' => $this->faker->imageUrl(530, 300),
            'img_02' => $this->faker->imageUrl(530, 675),
            'img_03' => $this->faker->imageUrl(530, 470),
        ];
    }
}
