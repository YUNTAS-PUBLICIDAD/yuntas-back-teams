<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DatosPersonal>
 */
class DatosPersonalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'datos' => $this->faker->name(),
            'tipo_doc' => $this->faker->randomElement(['DNI', 'Pasaporte', 'Carnet de Extranjeria']),
            'numero_doc' => $this->faker->numerify('########'),
            'correo' => $this->faker->email(),
            'telefono' => $this->faker->regexify('(9[0-9]{8})'),
        ];
    }
}
