<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->asRole(Role::Admin)->create([
            'name' => 'Admin',
            'email' => 'admin@antrian.test',
        ]);

        User::factory()->asRole(Role::Operator)->create([
            'name' => 'Operator',
            'email' => 'operator@antrian.test',
        ]);

        User::factory()->asRole(Role::Penjaga)->create([
            'name' => 'Penjaga',
            'email' => 'penjaga@antrian.test',
        ]);

        User::factory()->asRole(Role::Monitor)->create([
            'name' => 'Monitor',
            'email' => 'monitor@antrian.test',
        ]);

        $this->call([ServiceCategorySeeder::class, ServiceSeeder::class]);
    }
}
