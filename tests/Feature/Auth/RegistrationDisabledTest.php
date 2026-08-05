<?php

test('registration routes are disabled', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});
