<?php

use App\Enums\Role;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users are redirected to role dashboard', function () {
    $user = User::factory()->asRole(Role::Admin)->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('dashboard.admin'));
});

test('user can access own role dashboard', function () {
    $user = User::factory()->asRole(Role::Operator)->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.operator'));
    $response->assertOk();
});

test('user gets 403 on wrong role dashboard', function () {
    $user = User::factory()->asRole(Role::Penjaga)->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.admin'));
    $response->assertForbidden();
});

test('all roles can access their own dashboard', function () {
    foreach (Role::cases() as $role) {
        $user = User::factory()->asRole($role)->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard.'.$role->value));
        $response->assertOk();
    }
});
