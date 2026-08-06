<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word,
            'prefix' => strtoupper(fake()->unique()->bothify('???')),
            'digit_number' => 3,
            'start_number' => 1,
            'reset_number' => false,
            'is_active' => true,
            'service_category_id' => ServiceCategory::factory(),
        ];
    }
}
