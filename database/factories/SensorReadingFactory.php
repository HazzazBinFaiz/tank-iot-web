<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SensorReadingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'timestamp' => now()->subMinutes(rand(0, 15))->timestamp,
            'distance_cm' => $this->faker->randomFloat(0, 0, 22),
            'water_percent' => $this->faker->randomFloat(2, 0, 100),
            'air_temp' => $this->faker->randomFloat(0, 0, 100),
            'air_humidity' => $this->faker->randomFloat(0, 0, 100),
            'water_temp' => $this->faker->randomFloat(0, 0, 100),
            'tds' => $this->faker->randomFloat(2, 0, 1000),
            'pump_on' => $this->faker->boolean(),
        ];
    }
}
