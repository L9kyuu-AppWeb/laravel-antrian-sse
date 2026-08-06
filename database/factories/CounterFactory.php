<?php

namespace Database\Factories;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Counter>
 */
class CounterFactory extends Factory
{
    protected $model = Counter::class;

    public function definition(): array
    {
        return [
            'name' => 'Loket '.fake()->numberBetween(1, 20),
            'is_active' => true,
            'service_id' => Service::factory(),
        ];
    }
}
