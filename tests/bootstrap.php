<?php

use Duan\DuanApp;

require __DIR__ . '/../vendor/autoload.php';

function get_integration_test_app() {
    $app = new DuanApp('test');

    require __DIR__ . '/../bootstrap/middlewares.php';
    require __DIR__ . '/../bootstrap/routes.php';

    $app->boot();

    // Configure necessary values in $application

    // Disable Log output

    $app['faker'] = function () {
        $faker = Faker\Factory::create('it_IT');
        $faker->seed(100);
        return $faker;
    };

    // time to register other test required services

    return $app;
}

function get_test_app () {
    $app = get_integration_test_app();

    // Mocks repositories/managers when necessary
    return $app;
}
