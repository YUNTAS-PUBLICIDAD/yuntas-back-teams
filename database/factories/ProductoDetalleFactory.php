<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductoDetalle>
 */
class ProductoDetalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_produc' => \App\Models\Producto::factory(),
            'especificacion' => $this->faker->text(40),
            'informacion' => $this->faker->text(255),
            'beneficios_01' => $this->faker->text(40),
            'beneficios_02' => $this->faker->text(40),
            'beneficios_03' => $this->faker->text(40),
            'beneficios_04' => $this->faker->text(40),
            'img_card' => $this->faker->imageUrl(210, 270),
            'img_portada_01' => $this->faker->imageUrl(1920, 1080),
            'img_portada_02' => $this->faker->imageUrl(1920, 1080),
            'img_portada_03' => $this->faker->imageUrl(1920, 1080),
            'img_esp' => $this->faker->imageUrl(550, 400),
            'img_benef' => $this->faker->imageUrl(410, 530),
        ];
    }
}
