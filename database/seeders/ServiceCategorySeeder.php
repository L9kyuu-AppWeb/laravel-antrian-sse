<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Administrasi', 'description' => 'Layanan administrasi umum', 'is_active' => true],
            ['name' => 'Customer Service', 'description' => 'Pelayanan pelanggan', 'is_active' => true],
            ['name' => 'Akademik', 'description' => 'Layanan akademik', 'is_active' => true],
            ['name' => 'Poliklinik', 'description' => 'Layanan kesehatan poliklinik', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            ServiceCategory::query()->firstOrCreate(
                ['name' => $category['name']],
                $category,
            );
        }
    }
}
