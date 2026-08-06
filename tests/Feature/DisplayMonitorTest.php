<?php

use App\Enums\Role;
use App\Models\DisplayMonitor;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->asRole(Role::Admin)->create();
    $this->penjaga = User::factory()->asRole(Role::Penjaga)->create();
    $this->category = ServiceCategory::factory()->create(['name' => 'Administrasi']);
    $this->service1 = Service::factory()->create([
        'name' => 'Registrasi',
        'prefix' => 'REG',
        'service_category_id' => $this->category->id,
    ]);
    $this->service2 = Service::factory()->create([
        'name' => 'Customer Service',
        'prefix' => 'CS',
        'service_category_id' => $this->category->id,
    ]);
});

test('admin can view display monitors index', function () {
    $display = DisplayMonitor::factory()->create(['name' => 'TV Lobby']);
    $display->services()->sync([$this->service1->id, $this->service2->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.display-monitors.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('display-monitors/Index')
            ->has('displayMonitors', 1)
            ->where('displayMonitors.0.name', 'TV Lobby')
            ->has('displayMonitors.0.services', 2));
});

test('non-admin is forbidden from display monitors', function () {
    $this->actingAs($this->penjaga)
        ->get(route('admin.display-monitors.index'))
        ->assertForbidden();
});

test('admin can create a display monitor with multiple services', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.display-monitors.store'), [
            'name' => 'TV Lobby',
            'location' => 'Lobby Utama',
            'mode' => 'queue_calling',
            'services' => [$this->service1->id, $this->service2->id],
            'is_active' => 1,
        ])
        ->assertRedirect(route('admin.display-monitors.index'));

    $this->assertDatabaseHas('display_monitors', [
        'name' => 'TV Lobby',
        'location' => 'Lobby Utama',
        'mode' => 'queue_calling',
    ]);

    $display = DisplayMonitor::query()->where('name', 'TV Lobby')->first();
    $this->assertCount(2, $display->services);
});

test('display monitor name is required and unique', function () {
    DisplayMonitor::factory()->create(['name' => 'TV Lobby']);

    $this->actingAs($this->admin)
        ->post(route('admin.display-monitors.store'), [
            'name' => 'TV Lobby',
            'location' => 'Lobby',
            'mode' => 'queue_calling',
            'services' => [$this->service1->id],
        ])
        ->assertSessionHasErrors(['name']);
});

test('services array is required on create', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.display-monitors.store'), [
            'name' => 'TV Lobby',
            'location' => 'Lobby',
            'mode' => 'queue_calling',
            'services' => [],
        ])
        ->assertSessionHasErrors(['services']);
});

test('admin can update a display monitor and sync services', function () {
    $display = DisplayMonitor::factory()->create(['name' => 'TV Lobby']);
    $display->services()->sync([$this->service1->id]);

    $this->actingAs($this->admin)
        ->put(route('admin.display-monitors.update', $display), [
            'name' => 'TV Lobby',
            'location' => 'Lobby Utama',
            'mode' => 'queue_calling',
            'services' => [$this->service2->id],
            'is_active' => 0,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.display-monitors.index'));

    expect($display->fresh()->is_active)->toBeFalse();

    $syncedIds = $display->fresh()->services->pluck('id')->toArray();
    $this->assertEquals([$this->service2->id], $syncedIds);
});

test('admin can update a display monitor without changing its name', function () {
    $display = DisplayMonitor::factory()->create(['name' => 'TV Poliklinik']);
    $display->services()->sync([$this->service1->id]);

    $this->actingAs($this->admin)
        ->put(route('admin.display-monitors.update', $display), [
            'name' => 'TV Poliklinik',
            'location' => 'Lantai 2',
            'mode' => 'queue_calling',
            'services' => [$this->service1->id, $this->service2->id],
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.display-monitors.index'));

    $this->assertCount(2, $display->fresh()->services);
});

test('admin can delete a display monitor', function () {
    $display = DisplayMonitor::factory()->create();
    $display->services()->sync([$this->service1->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.display-monitors.destroy', $display))
        ->assertRedirect(route('admin.display-monitors.index'));

    $this->assertDatabaseMissing('display_monitors', ['id' => $display->id]);
    $this->assertDatabaseCount('display_monitor_service', 0);
});
