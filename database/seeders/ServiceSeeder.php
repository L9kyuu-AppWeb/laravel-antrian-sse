<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ServiceCategory::all();

        $services = [
            ['name' => 'Registrasi', 'prefix' => 'REG', 'digit_number' => 3, 'start_number' => 1, 'reset_number' => true],
            ['name' => 'Customer Service', 'prefix' => 'CS', 'digit_number' => 3, 'start_number' => 1, 'reset_number' => true],
            ['name' => 'Akademik', 'prefix' => 'AKD', 'digit_number' => 3, 'start_number' => 1, 'reset_number' => true],
            ['name' => 'Poliklinik', 'prefix' => 'POL', 'digit_number' => 3, 'start_number' => 1, 'reset_number' => true],
        ];

        foreach ($services as $service) {
            $category = ServiceCategory::query()
                ->where('name', $service['name'])
                ->first()
                ?? $categories->random();

            Service::query()->firstOrCreate(
                ['prefix' => $service['prefix']],
                array_merge($service, [
                    'is_active' => true,
                    'service_category_id' => $category->id,
                ]),
            );
        }
    }
}
