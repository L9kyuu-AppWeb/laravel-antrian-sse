<?php

use App\Enums\Role;
use App\Models\User;

test('login page exposes demo accounts for each role', function () {
    foreach (Role::cases() as $role) {
        User::factory()->asRole($role)->create(['email' => "{$role->value}@antrian.test"]);
    }

    $this->get('/login')
        ->assertInertia(fn ($page) => $page
            ->component('auth/Login')
            ->has('demoAccounts', count(Role::cases()))
            ->where('demoAccounts.0.email', Role::Admin->value.'@antrian.test')
            ->where('demoAccounts.1.email', Role::Operator->value.'@antrian.test')
            ->where('demoAccounts.2.email', Role::Penjaga->value.'@antrian.test')
            ->where('demoAccounts.3.email', Role::Monitor->value.'@antrian.test'));
});
