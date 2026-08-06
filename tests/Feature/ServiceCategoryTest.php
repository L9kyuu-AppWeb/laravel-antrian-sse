<?php

use App\Enums\Role;
use App\Models\ServiceCategory;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->asRole(Role::Admin)->create();
    $this->penjaga = User::factory()->asRole(Role::Penjaga)->create();
});

test('admin can view service categories index', function () {
    ServiceCategory::factory()->create(['name' => 'Administrasi']);

    $this->actingAs($this->admin)
        ->get(route('admin.service-categories.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('service-categories/Index')
            ->has('serviceCategories', 1)
            ->where('serviceCategories.0.name', 'Administrasi'));
});

test('non-admin is forbidden from service categories', function () {
    $this->actingAs($this->penjaga)
        ->get(route('admin.service-categories.index'))
        ->assertForbidden();
});

test('admin can create a service category', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.service-categories.store'), [
            'name' => 'Customer Service',
            'description' => 'Layanan pelanggan',
            'is_active' => 1,
        ])
        ->assertRedirect(route('admin.service-categories.index'));

    $this->assertDatabaseHas('service_categories', [
        'name' => 'Customer Service',
        'is_active' => 1,
    ]);
});

test('category name is required and unique', function () {
    ServiceCategory::factory()->create(['name' => 'Akademik']);

    $this->actingAs($this->admin)
        ->post(route('admin.service-categories.store'), [
            'name' => 'Akademik',
        ])
        ->assertSessionHasErrors(['name']);

    $this->actingAs($this->admin)
        ->post(route('admin.service-categories.store'), [
            'name' => '',
        ])
        ->assertSessionHasErrors(['name']);
});

test('admin can update a service category', function () {
    $category = ServiceCategory::factory()->create(['name' => 'Poliklinik']);

    $this->actingAs($this->admin)
        ->put(route('admin.service-categories.update', $category), [
            'name' => 'Poliklinik Umum',
            'description' => 'Layanan dokter umum',
            'is_active' => 0,
        ])
        ->assertRedirect(route('admin.service-categories.index'));

    expect($category->fresh())
        ->name->toBe('Poliklinik Umum')
        ->is_active->toBeFalse();
});

test('admin can update a service category without changing its name', function () {
    $category = ServiceCategory::factory()->create(['name' => 'Akademik']);

    $this->actingAs($this->admin)
        ->put(route('admin.service-categories.update', $category), [
            'name' => 'Akademik',
            'description' => 'Layanan akademik',
            'is_active' => 1,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.service-categories.index'));

    expect($category->fresh()->name)->toBe('Akademik');
});

test('admin can delete a service category', function () {
    $category = ServiceCategory::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.service-categories.destroy', $category))
        ->assertRedirect(route('admin.service-categories.index'));

    $this->assertDatabaseMissing('service_categories', ['id' => $category->id]);
});
