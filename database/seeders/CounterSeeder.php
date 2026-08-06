<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all();

        foreach ($services as $service) {
            Counter::query()->firstOrCreate(
                ['name' => 'Loket '.$service->name],
                [
                    'is_active' => true,
                    'service_id' => $service->id,
                ],
            );
        }
    }
}
