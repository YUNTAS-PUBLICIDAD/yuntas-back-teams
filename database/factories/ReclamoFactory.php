<?php

namespace Database\Factories;

use App\Models\DatosPersonal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reclamo>
 */
class ReclamoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha_compra' => $this->faker->date(),
            'producto' => $this->faker->word(),
            'detalle_reclamo' => $this->faker->paragraph(),
            'monto_reclamo' => $this->faker->randomFloat(2, 1, 100),
            'id_data' => DatosPersonal::inRandomOrder()->first()->id,
        ];
    }
}
