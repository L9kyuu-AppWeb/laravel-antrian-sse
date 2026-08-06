<?php

namespace Database\Factories;

use App\Models\DisplayMonitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DisplayMonitor>
 */
class DisplayMonitorFactory extends Factory
{
    protected $model = DisplayMonitor::class;

    public function definition(): array
    {
        return [
            'name' => 'TV '.fake()->word,
            'location' => fake()->word,
            'mode' => 'queue_calling',
            'is_active' => true,
        ];
    }
}
