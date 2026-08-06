<?php

use App\Enums\Role;
use App\Models\Counter;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->asRole(Role::Admin)->create();
    $this->penjaga = User::factory()->asRole(Role::Penjaga)->create();
    $this->category = ServiceCategory::factory()->create(['name' => 'Administrasi']);
    $this->service = Service::factory()->create([
        'name' => 'Registrasi',
        'prefix' => 'REG',
        'service_category_id' => $this->category->id,
    ]);
});

test('admin can view counters index', function () {
    Counter::factory()->create([
        'name' => 'Loket 1',
        'service_id' => $this->service->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.counters.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('counters/Index')
            ->has('counters', 1)
            ->where('counters.0.name', 'Loket 1'));
});

test('non-admin is forbidden from counters', function () {
    $this->actingAs($this->penjaga)
        ->get(route('admin.counters.index'))
        ->assertForbidden();
});

test('admin can create a counter', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.counters.store'), [
            'name' => 'Loket 1',
            'service_id' => $this->service->id,
            'is_active' => 1,
        ])
        ->assertRedirect(route('admin.counters.index'));

    $this->assertDatabaseHas('counters', [
        'name' => 'Loket 1',
        'service_id' => $this->service->id,
        'is_active' => 1,
    ]);
});

test('counter name is required and unique', function () {
    Counter::factory()->create(['name' => 'Loket 1', 'service_id' => $this->service->id]);

    $this->actingAs($this->admin)
        ->post(route('admin.counters.store'), [
            'name' => 'Loket 1',
            'service_id' => $this->service->id,
        ])
        ->assertSessionHasErrors(['name']);

    $this->actingAs($this->admin)
        ->post(route('admin.counters.store'), [
            'name' => '',
            'service_id' => $this->service->id,
        ])
        ->assertSessionHasErrors(['name']);
});

test('service must exist', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.counters.store'), [
            'name' => 'Loket 1',
            'service_id' => 9999,
        ])
        ->assertSessionHasErrors(['service_id']);
});

test('admin can update a counter without changing its name', function () {
    $counter = Counter::factory()->create([
        'name' => 'Loket 1',
        'service_id' => $this->service->id,
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.counters.update', $counter), [
            'name' => 'Loket 1',
            'service_id' => $this->service->id,
            'is_active' => 0,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.counters.index'));

    expect($counter->fresh()->is_active)->toBeFalse();
});

test('admin can delete a counter', function () {
    $counter = Counter::factory()->create(['service_id' => $this->service->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.counters.destroy', $counter))
        ->assertRedirect(route('admin.counters.index'));

    $this->assertDatabaseMissing('counters', ['id' => $counter->id]);
});
