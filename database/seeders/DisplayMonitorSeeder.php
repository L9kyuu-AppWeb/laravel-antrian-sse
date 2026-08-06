<?php

namespace Database\Seeders;

use App\Models\DisplayMonitor;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DisplayMonitorSeeder extends Seeder
{
    public function run(): void
    {
        $serviceRegistrasi = Service::query()->where('prefix', 'REG')->first();
        $serviceCS = Service::query()->where('prefix', 'CS')->first();
        $servicePoliklinik = Service::query()->where('prefix', 'POL')->first();

        $tvLobby = DisplayMonitor::query()->firstOrCreate(
            ['name' => 'TV Lobby'],
            [
                'location' => 'Lobby Utama',
                'mode' => 'queue_calling',
                'is_active' => true,
            ],
        );

        $tvPoli = DisplayMonitor::query()->firstOrCreate(
            ['name' => 'TV Poliklinik'],
            [
                'location' => 'Lantai 2',
                'mode' => 'queue_calling',
                'is_active' => true,
            ],
        );

        if ($serviceRegistrasi) {
            $tvLobby->services()->syncWithoutDetaching($serviceRegistrasi->id);
        }

        if ($serviceCS) {
            $tvLobby->services()->syncWithoutDetaching($serviceCS->id);
        }

        if ($servicePoliklinik) {
            $tvPoli->services()->syncWithoutDetaching($servicePoliklinik->id);
            $tvLobby->services()->syncWithoutDetaching($servicePoliklinik->id);
        }
    }
}
