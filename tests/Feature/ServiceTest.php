<?php

use App\Enums\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->asRole(Role::Admin)->create();
    $this->penjaga = User::factory()->asRole(Role::Penjaga)->create();
    $this->category = ServiceCategory::factory()->create(['name' => 'Administrasi']);
});

test('admin can view services index', function () {
    Service::factory()->create([
        'name' => 'Registrasi',
        'prefix' => 'REG',
        'service_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.services.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('services/Index')
            ->has('services', 1)
            ->where('services.0.name', 'Registrasi')
            ->where('services.0.prefix', 'REG'));
});

test('non-admin is forbidden from services', function () {
    $this->actingAs($this->penjaga)
        ->get(route('admin.services.index'))
        ->assertForbidden();
});

test('admin can create a service', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), [
            'name' => 'Registrasi',
            'prefix' => 'REG',
            'digit_number' => 3,
            'start_number' => 1,
            'reset_number' => 1,
            'is_active' => 1,
            'service_category_id' => $this->category->id,
        ])
        ->assertRedirect(route('admin.services.index'));

    $this->assertDatabaseHas('services', [
        'name' => 'Registrasi',
        'prefix' => 'REG',
        'digit_number' => 3,
        'start_number' => 1,
        'reset_number' => 1,
        'service_category_id' => $this->category->id,
    ]);
});

test('service name and prefix are required', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), [
            'name' => '',
            'prefix' => '',
            'service_category_id' => $this->category->id,
        ])
        ->assertSessionHasErrors(['name', 'prefix']);

    $this->assertDatabaseCount('services', 0);
});

test('service prefix must be unique', function () {
    Service::factory()->create(['prefix' => 'REG', 'service_category_id' => $this->category->id]);

    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), [
            'name' => 'Registrasi',
            'prefix' => 'REG',
            'digit_number' => 3,
            'start_number' => 1,
            'service_category_id' => $this->category->id,
        ])
        ->assertSessionHasErrors(['prefix']);
});

test('service category must exist', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), [
            'name' => 'Registrasi',
            'prefix' => 'REG',
            'service_category_id' => 9999,
        ])
        ->assertSessionHasErrors(['service_category_id']);
});

test('admin can update a service without changing its prefix', function () {
    $service = Service::factory()->create([
        'prefix' => 'REG',
        'service_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.services.update', $service), [
            'name' => 'Registrasi Baru',
            'prefix' => 'REG',
            'digit_number' => 4,
            'start_number' => 1,
            'reset_number' => 0,
            'is_active' => 1,
            'service_category_id' => $this->category->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.services.index'));

    expect($service->fresh())
        ->name->toBe('Registrasi Baru')
        ->digit_number->toBe(4);
});

test('admin can delete a service', function () {
    $service = Service::factory()->create(['service_category_id' => $this->category->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.services.destroy', $service))
        ->assertRedirect(route('admin.services.index'));

    $this->assertDatabaseMissing('services', ['id' => $service->id]);
});
